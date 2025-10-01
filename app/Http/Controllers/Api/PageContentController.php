<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PageContent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PageContentController extends Controller
{
    private function editorUserId(): ?int
    {
        $u = auth()->user();
        return ($u instanceof User) ? $u->id : null;
    }
    // --- Image utilities (GD-based, no external deps) ---
    private function gdCanProcess(): bool
    {
        return function_exists('imagecreatetruecolor') && function_exists('imagecopyresampled');
    }

    private function gdCreateFromPath(string $path)
    {
        $info = @getimagesize($path);
        if (!$info || !isset($info[2])) return [null, null, null];
        $mime = $info['mime'] ?? '';
        $type = $info[2]; // IMAGETYPE_*
        try {
            switch ($type) {
                case IMAGETYPE_JPEG: return [imagecreatefromjpeg($path), $mime, 'jpg'];
                case IMAGETYPE_PNG: return [imagecreatefrompng($path), $mime, 'png'];
                case IMAGETYPE_WEBP: if (function_exists('imagecreatefromwebp')) return [imagecreatefromwebp($path), $mime, 'webp']; else return [null, null, null];
                case IMAGETYPE_GIF: return [imagecreatefromgif($path), $mime, 'gif'];
                default: return [null, null, null]; // svg, bmp 等は非対応
            }
        } catch (\Throwable $e) {
            return [null, null, null];
        }
    }

    private function gdSaveToPath($image, string $path, string $ext): bool
    {
        try {
            switch (strtolower($ext)) {
                case 'jpg':
                case 'jpeg':
                    return imagejpeg($image, $path, 85);
                case 'png':
                    // 0 (no compression) to 9
                    return imagepng($image, $path, 6);
                case 'webp':
                    if (function_exists('imagewebp')) return imagewebp($image, $path, 85);
                    return false;
                case 'gif':
                    return imagegif($image, $path);
                default:
                    return false;
            }
        } catch (\Throwable $e) {
            return false;
        }
    }

    // Resize and center-crop to target size (cover behavior)
    private function gdResizeCoverTo(string $srcPath, string $dstPath, int $tw, int $th): bool
    {
        if ($tw <= 0 || $th <= 0) return false;
        if (!$this->gdCanProcess()) return false;
        [$src, $mime, $ext] = $this->gdCreateFromPath($srcPath);
        if (!$src) return false;
        $srcInfo = @getimagesize($srcPath);
        $sw = (int)($srcInfo[0] ?? 0); $sh = (int)($srcInfo[1] ?? 0);
        if ($sw <= 0 || $sh <= 0) { imagedestroy($src); return false; }

        $scale = max($tw / $sw, $th / $sh);
        $nw = (int)ceil($sw * $scale);
        $nh = (int)ceil($sh * $scale);

        $tmp = imagecreatetruecolor($nw, $nh);
        // preserve transparency for PNG/GIF
        imagealphablending($tmp, false);
        imagesavealpha($tmp, true);
        imagecopyresampled($tmp, $src, 0, 0, 0, 0, $nw, $nh, $sw, $sh);

        // crop center to target
        $dx = (int)max(0, ($nw - $tw) / 2);
        $dy = (int)max(0, ($nh - $th) / 2);
        $dst = imagecreatetruecolor($tw, $th);
        imagealphablending($dst, false);
        imagesavealpha($dst, true);
        imagecopy($dst, $tmp, 0, 0, $dx, $dy, min($tw, $nw), min($th, $nh));

        // Save
        $ok = $this->gdSaveToPath($dst, $dstPath, $ext ?: pathinfo($dstPath, PATHINFO_EXTENSION));
        imagedestroy($src); imagedestroy($tmp); imagedestroy($dst);
        return $ok;
    }
    /**
     * Recursively collect image references from nested JSON content.
     * Supports structures like nested 'images' maps and common single image fields.
     * Returns a flat list of items with dot-notation keys.
     */
    private function collectNestedImages(array $content, string $pageKey, $pageUpdatedAt): array
    {
        $items = [];
        $stack = [ [ 'node' => $content, 'path' => [] ] ];
        $singleFields = ['image', 'image_url', 'img', 'src', 'background', 'background_image', 'backgroundImage'];

        while (!empty($stack)) {
            $frame = array_pop($stack);
            $node = $frame['node'];
            $path = $frame['path'];

            if (!is_array($node)) {
                continue;
            }

            if (isset($node['images']) && is_array($node['images'])) {
                foreach ($node['images'] as $k => $val) {
                    $url = null; $p = null; $filename = null;
                    if (is_string($val)) {
                        $url = $val;
                    } elseif (is_array($val)) {
                        $url = $val['url'] ?? null;
                        $p = $val['path'] ?? null;
                        $filename = $val['filename'] ?? null;
                    }
                    if ($url) {
                        $items[] = [
                            'page_key' => $pageKey,
                            'model' => 'page_content',
                            'id' => null,
                            'key' => (count($path) ? implode('.', $path) . '.' : '') . 'images.' . (string)$k,
                            'url' => $url,
                            'path' => $p,
                            'filename' => $filename,
                            'source' => 'json',
                            'updated_at' => $pageUpdatedAt,
                        ];
                    }
                }
            }

            foreach ($singleFields as $field) {
                if (array_key_exists($field, $node)) {
                    $val = $node[$field];
                    $url = null; $p = null; $filename = null;
                    if (is_string($val)) {
                        $url = $val;
                    } elseif (is_array($val)) {
                        $url = $val['url'] ?? null;
                        $p = $val['path'] ?? null;
                        $filename = $val['filename'] ?? null;
                    }
                    if ($url) {
                        $items[] = [
                            'page_key' => $pageKey,
                            'model' => 'page_content',
                            'id' => null,
                            'key' => (count($path) ? implode('.', $path) . '.' : '') . $field,
                            'url' => $url,
                            'path' => $p,
                            'filename' => $filename,
                            'source' => 'json',
                            'updated_at' => $pageUpdatedAt,
                        ];
                    }
                }
            }

            foreach ($node as $k => $child) {
                if (is_array($child)) {
                    $nextPath = $path;
                    $nextPath[] = (string)$k;
                    $stack[] = [ 'node' => $child, 'path' => $nextPath ];
                }
            }
        }

        return $items;
    }

    private function setByPath(array $array, string $path, $value): array
    {
        $segments = explode('.', $path);
        $ref =& $array;
        foreach ($segments as $seg) {
            $index = ctype_digit((string)$seg) ? (int)$seg : $seg;
            if (!is_array($ref)) {
                $ref = [];
            }
            if (!array_key_exists($index, $ref) || !is_array($ref[$index])) {
                $ref[$index] = [];
            }
            $ref =& $ref[$index];
        }
        $ref = $value;
        return $array;
    }

    private function getByPath($array, string $path): array
    {
        $segments = explode('.', $path);
        $ref = $array;
        foreach ($segments as $seg) {
            $index = ctype_digit((string)$seg) ? (int)$seg : $seg;
            if (is_array($ref) && array_key_exists($index, $ref)) {
                $ref = $ref[$index];
            } else {
                return [false, null];
            }
        }
        return [true, $ref];
    }

    // Build safe, styled HTML from structured history[] for fallback rendering
    private function buildHistoryHtml(array $history): string
    {
        $buf = [];
        $buf[] = '<div class="history-content">';
        foreach ($history as $h) {
            if (!is_array($h)) continue;
            $year = isset($h['year']) ? (string)$h['year'] : '';
            $date = isset($h['date']) ? (string)$h['date'] : '';
            // body may contain simple inline HTML authored by editors; keep as-is here, FE sanitizes on render
            $body = isset($h['body']) ? (string)$h['body'] : (isset($h['title']) ? (string)$h['title'] : '');
            $ey = htmlspecialchars($year, ENT_QUOTES, 'UTF-8');
            $ed = htmlspecialchars($date, ENT_QUOTES, 'UTF-8');
            $buf[] = '<div class="history-item">'
                . '<div class="history-year">' . $ey . '</div>'
                . '<div class="history-details">'
                .   '<div class="history-date">' . $ed . '</div>'
                .   '<div class="history-description">' . $body . '</div>'
                . '</div>'
              . '</div>';
        }
        $buf[] = '</div>';
        return implode('', $buf);
    }
    public function mediaUsage(): JsonResponse
    {
        $pages = PageContent::all();
        // Optionally include model rows (publications/seminars/economic reports) when explicitly requested.
        // Default is OFF to avoid mixing page media with model thumbnails in Media Management UI.
        $includeModels = (string) (request()->query('include_models', '0')) === '1';
        $items = [];

        foreach ($pages as $page) {
            $content = $page->content;

            // 1) JSON images registry: content.images[<type>] = { url, ... } or string
            if (is_array($content) && isset($content['images']) && is_array($content['images'])) {
                foreach ($content['images'] as $key => $val) {
                    $url = null;
                    $path = null;
                    $filename = null;
                    if (is_string($val)) {
                        $url = $val;
                    } elseif (is_array($val)) {
                        $url = $val['url'] ?? null;
                        $path = $val['path'] ?? null;
                        $filename = $val['filename'] ?? null;
                    }
                    if ($url) {
                        $items[] = [
                            'page_key' => $page->page_key,
                            'model' => 'page_content',
                            'id' => $page->id,
                            'key' => (string)$key,
                            'url' => $url,
                            'path' => $path,
                            'filename' => $filename,
                            'source' => 'json',
                            'updated_at' => $page->updated_at,
                        ];
                    }
                }
            }

            // 2) HTML body: scan for <img src="...">
            $html = null;
            if (is_string($content)) {
                $html = $content;
            } elseif (is_array($content) && isset($content['html']) && is_string($content['html'])) {
                $html = $content['html'];
            }
            if (is_string($html) && strlen($html)) {
                // Lightweight regex to extract image sources
                if (preg_match_all('/<img[^>]+src=["\']([^"\']+)["\']/i', $html, $m)) {
                    foreach ($m[1] as $src) {
                        $items[] = [
                            'page_key' => $page->page_key,
                            'model' => 'page_content',
                            'id' => $page->id,
                            'key' => 'html',
                            'url' => $src,
                            'path' => null,
                            'filename' => null,
                            'source' => 'html',
                            'updated_at' => $page->updated_at,
                        ];
                    }
                }
            }
        }

        // Deep scan nested image fields inside PageContent JSON (child components etc.)
        foreach ($pages as $page) {
            $content = $page->content;
            if (is_array($content)) {
                $nested = $this->collectNestedImages($content, $page->page_key, $page->updated_at);
                // Avoid duplicates (page_key + key + url)
                foreach ($nested as $n) {
                    $exists = false;
                    foreach ($items as $it) {
                        if ($it['page_key'] === $n['page_key'] && $it['key'] === $n['key'] && (string)$it['url'] === (string)$n['url']) {
                            $exists = true; break;
                        }
                    }
                    if (!$exists) { $items[] = $n; }
                }
            }
        }

        // 追加: 他モデルの画像参照も集計
        try {
            // News (CMS版)
            if (class_exists(\App\Models\News::class)) {
                $allNews = \App\Models\News::query()->get();
                foreach ($allNews as $news) {
                    // featured_image
                    if (!empty($news->featured_image)) {
                        $items[] = [
                            'page_key' => 'news',
                            'model' => 'news',
                            'id' => $news->id,
                            'key' => 'featured_image',
                            'url' => (string)$news->featured_image,
                            'path' => null,
                            'filename' => null,
                            'source' => 'column',
                            'updated_at' => $news->updated_at,
                        ];
                    }
                    // content の <img>
                    $html = (string)($news->content ?? '');
                    if ($html && preg_match_all('/<img[^>]+src=["\']([^"\']+)["\']/i', $html, $m)) {
                        foreach ($m[1] as $src) {
                            $items[] = [
                                'page_key' => 'news',
                                'model' => 'news',
                                'id' => $news->id,
                                'key' => 'html',
                                'url' => $src,
                                'path' => null,
                                'filename' => null,
                                'source' => 'html',
                                'updated_at' => $news->updated_at,
                            ];
                        }
                    }
                }
            }
        } catch (\Throwable $e) {
            // 例外は握りつぶして続行（環境差異吸収）
        }

        try {
            // Notice (NewsArticle)
            if (class_exists(\App\Models\NewsArticle::class)) {
                $articles = \App\Models\NewsArticle::query()->get();
                foreach ($articles as $a) {
                    if (!empty($a->featured_image)) {
                        $items[] = [
                            'page_key' => 'notice',
                            'model' => 'news_article',
                            'id' => $a->id,
                            'key' => 'featured_image',
                            'url' => (string)$a->featured_image,
                            'path' => null,
                            'filename' => null,
                            'source' => 'column',
                            'updated_at' => $a->updated_at,
                        ];
                    }
                    $html = (string)($a->content ?? '');
                    if ($html && preg_match_all('/<img[^>]+src=["\']([^"\']+)["\']/i', $html, $m)) {
                        foreach ($m[1] as $src) {
                            $items[] = [
                                'page_key' => 'notice',
                                'model' => 'news_article',
                                'id' => $a->id,
                                'key' => 'html',
                                'url' => $src,
                                'path' => null,
                                'filename' => null,
                                'source' => 'html',
                                'updated_at' => $a->updated_at,
                            ];
                        }
                    }
                }
            }
        } catch (\Throwable $e) {}

        if ($includeModels) {
            try {
                if (class_exists(\App\Models\Publication::class)) {
                    $pubs = \App\Models\Publication::query()->get();
                    foreach ($pubs as $p) {
                        $url = (string)($p->cover_image ?? '');
                        $items[] = [
                            'page_key' => 'publications',
                            'model' => 'publication',
                            'id' => $p->id,
                            'key' => 'cover_image',
                            'url' => $url,
                            'path' => null,
                            'filename' => null,
                            'source' => 'column',
                            'updated_at' => $p->updated_at,
                        ];
                    }
                }
            } catch (\Throwable $e) {}
        }

        if ($includeModels) {
            try {
                if (class_exists(\App\Models\EconomicReport::class)) {
                    $reps = \App\Models\EconomicReport::query()->get();
                    foreach ($reps as $r) {
                        $url = (string)($r->cover_image ?? '');
                        $items[] = [
                            'page_key' => 'economic-reports',
                            'model' => 'economic_report',
                            'id' => $r->id,
                            'key' => 'cover_image',
                            'url' => $url,
                            'path' => null,
                            'filename' => null,
                            'source' => 'column',
                            'updated_at' => $r->updated_at,
                        ];
                    }
                }
            } catch (\Throwable $e) {}
        }

        if ($includeModels) {
            try {
                if (class_exists(\App\Models\Seminar::class)) {
                    $sems = \App\Models\Seminar::query()->get();
                    foreach ($sems as $s) {
                        $url = (string)($s->featured_image ?? '');
                        $items[] = [
                            'page_key' => 'seminars',
                            'model' => 'seminar',
                            'id' => $s->id,
                            'key' => 'featured_image',
                            'url' => $url,
                            'path' => null,
                            'filename' => null,
                            'source' => 'column',
                            'updated_at' => $s->updated_at,
                        ];
                    }
                }
            } catch (\Throwable $e) {}
        }

        // 追加: コード上で使用しているが media レジストリに未登録の共有画像キーを補完
        try {
            // Policy: hero_* images are now managed per page, not globally.
            // Do NOT include any hero_* keys here.
            $companyKeys = [
                'company_profile_philosophy', 'company_profile_message',
                'company_profile_staff_morita', 'company_profile_staff_mizokami',
                'company_profile_staff_kuga', 'company_profile_staff_takada',
                'company_profile_staff_nakamura',
            ];
            $sectionKeys = [ 'contact_section_bg' ];

            $variants = function(array $keys) {
                $out = [];
                foreach ($keys as $k) {
                    $out[] = $k;
                    $out[] = $k . '_mobile';
                    $out[] = $k . '_tablet';
                }
                return $out;
            };
            $heroKeys = array_merge($variants($companyKeys), $variants($sectionKeys));
            $hasItem = function($k) use ($items) {
                foreach ($items as $it) {
                    if (($it['page_key'] ?? '') === 'media' && ($it['key'] ?? '') === $k) return true;
                }
                return false;
            };
            $mediaPage = null;
            foreach ($pages as $p) { if ($p->page_key === 'media') { $mediaPage = $p; break; } }
            foreach ($heroKeys as $k) {
                if (!$hasItem($k)) {
                    $items[] = [
                        'page_key' => 'media',
                        'model' => 'page_content',
                        'id' => $mediaPage?->id,
                        'key' => $k,
                        'url' => '/img/hero-image.png',
                        'path' => null,
                        'filename' => null,
                        // JSON レジストリとして扱い、置換時は replace-image を使う
                        'source' => 'json',
                        'updated_at' => $mediaPage?->updated_at,
                    ];
                }
            }
        } catch (\Throwable $e) { /* noop */ }

        // URL正規化
        foreach ($items as &$it) {
            $u = (string)($it['url'] ?? '');
            if ($u === '') continue;
            $lower = strtolower($u);
            $isAbsolute = str_starts_with($lower, 'http://') || str_starts_with($lower, 'https://') || str_starts_with($u, '//');
            if ($isAbsolute) {
                // keep absolute URLs
                continue;
            }

            // keep app-root relative assets as-is (e.g. '/img/...', '/assets/...')
            if (str_starts_with($u, '/img/') || str_starts_with($u, '/images/') || str_starts_with($u, '/assets/') || str_starts_with($u, '/favicon')) {
                continue;
            }

            // already normalized storage path
            if (str_starts_with($u, '/storage/')) {
                continue;
            }

            // 'storage/...'
            if (str_starts_with($u, 'storage/')) {
                $it['url'] = '/' . $u;
                continue;
            }

            // other relative paths without leading slash: try public disk mapping
            if (!str_starts_with($u, '/')) {
                $path = ltrim(preg_replace('/^public\//', '', $u), '/');
                $it['url'] = Storage::disk('public')->url($path); // '/storage/...'
                continue;
            }

            // default: leave as-is (other root-relative, e.g. '/pages/...')
        }

        return response()->json([
            'success' => true,
            'data' => [
                'items' => $items,
                'count' => count($items),
            ]
        ]);
    }
    public function index(): JsonResponse
    {
        $pages = PageContent::all();
        return response()->json([
            'success' => true,
            'data' => [
                'pages' => $pages
            ]
        ]);
    }

    public function show(string $pageKey): JsonResponse
    {
        $page = PageContent::where('page_key', $pageKey)
            ->where('is_published', true)
            ->first();

        if (!$page) {
            return response()->json([
                'success' => false,
                'message' => 'Page not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'page' => $page
            ]
        ]);
    }

    // 管理者向け: 公開状態に関わらず取得
    public function adminShow(string $pageKey): JsonResponse
    {
        $page = PageContent::where('page_key', $pageKey)->first();

        if (!$page) {
            // Auto-create media registry if missing to avoid FE 404 loops
            if ($pageKey === 'media') {
                $page = PageContent::create([
                    'page_key' => 'media',
                    'title' => 'メディアレジストリ',
                    'content' => [ 'images' => [] ],
                    'is_published' => true,
                    'published_at' => now(),
                    'updated_by' => $this->editorUserId(),
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Page not found'
                ], 404);
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'page' => $page
            ]
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'page_key' => 'required|string|unique:page_contents',
            'title' => 'required|string',
            // JSON or raw HTML/string どちらも許容
            'content' => 'required',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        // content が文字列ならラップして保存（互換保持）
        $content = $validated['content'];
        if (is_string($content)) {
            $validated['content'] = [ 'html' => $content ];
        }

        $validated['updated_by'] = $this->editorUserId();
        $page = PageContent::create($validated);

        return response()->json($page, 201);
    }

    public function update(Request $request, string $pageKey): JsonResponse
    {
        $page = PageContent::where('page_key', $pageKey)->first();

        if (!$page) {
            return response()->json(['message' => 'Page not found'], 404);
        }

        $validated = $request->validate([
            'title' => 'sometimes|string',
            // JSONまたは文字列どちらも許容
            'content' => 'sometimes',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        // Normalize and deep-merge content to avoid wiping unrelated keys
        if (array_key_exists('content', $validated)) {
            // Normalize incoming content if it's a raw HTML string
            if (is_string($validated['content'])) {
                $validated['content'] = [ 'html' => $validated['content'] ];
            }

            // Existing content from DB (ensure array form)
            $existing = $page->content;
            if (is_string($existing)) {
                $existing = [ 'html' => (string)$existing ];
            }
            if (!is_array($existing)) {
                $existing = [];
            }

            // Only merge when incoming is array; otherwise leave as-is
            if (is_array($validated['content'])) {
                // For list-type fields (numeric arrays), we want full replacement rather than index-wise merge.
                // Prepare incoming copy and merged base.
                $incoming = $validated['content'];
                $merged = $existing;

                // Keys that must be replaced entirely when provided
                // Note: arrays like 'financial_reports' are top-level lists and should fully replace
                $replaceListKeys = ['history', 'items', 'financial_reports', 'staff'];
                foreach ($replaceListKeys as $rk) {
                    if (array_key_exists($rk, $incoming) && is_array($incoming[$rk])) {
                        $merged[$rk] = $incoming[$rk];
                        unset($incoming[$rk]);
                    }
                }

                // Deep-merge the rest (associative maps like texts/htmls/images)
                $merged = array_replace_recursive($merged, $incoming);

                // Auto-synthesize HTML fallback for history when structured array is present
                try {
                    if (isset($merged['history']) && is_array($merged['history'])) {
                        $html = $this->buildHistoryHtml($merged['history']);
                        if (!isset($merged['htmls']) || !is_array($merged['htmls'])) { $merged['htmls'] = []; }
                        $merged['htmls']['history_body'] = $html;
                    }
                } catch (\Throwable $e) { /* ignore synthesis errors */ }

                $validated['content'] = $merged;
            }
        }

        $validated['updated_by'] = $this->editorUserId();
        $page->update($validated);

        return response()->json($page);
    }

    public function destroy(string $pageKey): JsonResponse
    {
        $page = PageContent::where('page_key', $pageKey)->first();

        if (!$page) {
            return response()->json(['message' => 'Page not found'], 404);
        }

        $page->delete();
        return response()->json(['message' => 'Page deleted successfully']);
    }

    

    public function uploadImage(Request $request, string $pageKey): JsonResponse
    {
        $page = PageContent::where('page_key', $pageKey)->first();

        if (!$page) {
            return response()->json(['message' => 'Page not found'], 404);
        }

        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            'type' => 'required|in:hero,content,gallery'
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = Str::slug($pageKey) . '-' . $request->type . '-' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('pages/' . $pageKey, $fileName, 'public');

            // contentの中に画像情報を保存
            $content = $page->content ?? [];
            if (!isset($content['images'])) {
                $content['images'] = [];
            }
            
            $content['images'][$request->type] = [
                'url' => Storage::disk('public')->url($path),
                'path' => $path,
                'filename' => $fileName,
                'uploaded_at' => now()
            ];

            $page->update([
                'content' => $content,
                'updated_by' => $this->editorUserId()
            ]);

            return response()->json([
                'message' => 'Image uploaded successfully',
                'image' => $content['images'][$request->type]
            ]);
        }

        return response()->json(['message' => 'No image file provided'], 400);
    }

    public function deleteImage(Request $request, string $pageKey): JsonResponse
    {
        $page = PageContent::where('page_key', $pageKey)->first();

        if (!$page) {
            return response()->json(['message' => 'Page not found'], 404);
        }

        $request->validate([
            'type' => 'required|in:hero,content,gallery'
        ]);

        $content = $page->content ?? [];
        
        if (isset($content['images'][$request->type])) {
            // ストレージから画像を削除
            if (isset($content['images'][$request->type]['path'])) {
                Storage::disk('public')->delete($content['images'][$request->type]['path']);
            }
            
            // contentから画像情報を削除
            unset($content['images'][$request->type]);
            
            $page->update([
                'content' => $content,
                'updated_by' => $this->editorUserId()
            ]);

            return response()->json(['message' => 'Image deleted successfully']);
        }

        return response()->json(['message' => 'Image not found'], 404);
    }

    // 管理: content.images[<key>] を差し替え
    public function replaceImage(Request $request, string $pageKey): JsonResponse
    {
        $page = PageContent::where('page_key', $pageKey)->first();
        if (!$page) {
            // Safety: auto-create media registry if missing so FE can resolve useMedia()
            if ($pageKey === 'media') {
                $page = PageContent::create([
                    'page_key' => 'media',
                    'title' => 'メディアレジストリ',
                    'content' => [ 'images' => [] ],
                    'is_published' => true,
                    'published_at' => now(),
                    'updated_by' => $this->editorUserId(),
                ]);
            } else {
                return response()->json(['message' => 'Page not found'], 404);
            }
        }

        $request->validate([
            'key' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
        ]);

        // メディアレジストリ内のKV(= hero_*)はMedia管理からの編集を禁止（各ページのKV専用UIを使用）
        $reqKey = (string)$request->key;
        if ($pageKey === 'media' && str_starts_with($reqKey, 'hero_')) {
            return response()->json([
                'success' => false,
                'message' => 'KV画像はメディア管理から変更できません。各ページのKVアップローダーをご利用ください。'
            ], 403);
        }

        $file = $request->file('image');
        $fileName = Str::slug($pageKey) . '-' . Str::slug($request->key) . '-' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('pages/' . $pageKey, $fileName, 'public');

        $content = $page->content ?? [];
        if (!is_array($content)) { $content = ['html' => (string)$content]; }

        $newValue = [
            'url' => Storage::disk('public')->url($path),
            'path' => $path,
            'filename' => $fileName,
            'uploaded_at' => now()
        ];

        $key = $request->key;
        if (strpos($key, '.') !== false) {
            [$exists, $current] = $this->getByPath($content, $key);
            if ($exists) {
                if (is_array($current)) {
                    $content = $this->setByPath($content, $key, array_merge($current, $newValue));
                } else {
                    $content = $this->setByPath($content, $key, $newValue['url']);
                }
            } else {
                $content = $this->setByPath($content, $key, $newValue);
            }
        } else {
            $content['images'] = $content['images'] ?? [];
            $content['images'][$key] = $newValue;
        }

        // 既存の画像寸法を維持するため、差し替え先の従前画像サイズに合わせて新画像をリサイズ（cover）
        // hero_*（KV）はここには来ない（上で403）
        try {
            // find previous entry to extract target dimensions
            $prev = null;
            if (strpos($key, '.') !== false) {
                [$prevExists, $prevVal] = $this->getByPath($page->content ?? [], $key);
                $prev = $prevExists ? $prevVal : null;
            } else {
                $prev = $page->content['images'][$key] ?? null;
            }

            // Resolve previous image file path (public disk)
            $prevUrl = null; $prevPath = null;
            if (is_array($prev)) {
                $prevUrl = $prev['url'] ?? null;
                $prevPath = $prev['path'] ?? null;
            } elseif (is_string($prev)) {
                $prevUrl = $prev;
            }

            $prevDiskPath = null;
            if ($prevPath) {
                $prevDiskPath = Storage::disk('public')->path($prevPath);
            } elseif ($prevUrl && is_string($prevUrl) && str_starts_with($prevUrl, '/storage/')) {
                $rel = ltrim(substr($prevUrl, strlen('/storage/')), '/');
                $prevDiskPath = Storage::disk('public')->path($rel);
            }

            if ($prevDiskPath && @is_file($prevDiskPath)) {
                $dim = @getimagesize($prevDiskPath);
                $tw = (int)($dim[0] ?? 0); $th = (int)($dim[1] ?? 0);
                if ($tw > 0 && $th > 0) {
                    $dstPath = Storage::disk('public')->path($path);
                    // Try resizing only for raster images (skip svg etc.)
                    if ($this->gdCanProcess()) {
                        $this->gdResizeCoverTo($dstPath, $dstPath, $tw, $th);
                    }
                }
            }
        } catch (\Throwable $e) {
            // best-effort; ignore failures
        }

        $page->update([
            'content' => $content,
            'updated_by' => $this->editorUserId()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Image replaced',
            'data' => [ 'image' => $newValue ]
        ]);
    }

    // 管理: content.html 内の <img src> を置換
    public function replaceHtmlImage(Request $request, string $pageKey): JsonResponse
    {
        $page = PageContent::where('page_key', $pageKey)->first();
        if (!$page) {
            return response()->json(['message' => 'Page not found'], 404);
        }

        $request->validate([
            'old_url' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
        ]);

        $content = $page->content ?? [];
        $html = '';
        if (is_string($content)) { $html = $content; $content = ['html' => $html]; }
        elseif (is_array($content)) { $html = (string)($content['html'] ?? ''); }

        if ($html === '') {
            return response()->json(['success' => false, 'message' => 'HTML content not found'], 400);
        }

        $file = $request->file('image');
        $fileName = Str::slug($pageKey) . '-htmlimg-' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('pages/' . $pageKey, $fileName, 'public');
        $newUrl = Storage::disk('public')->url($path);

        // 既存のHTML内画像(old_url)の寸法を可能であれば取得し、新しい画像を同寸法にリサイズ
        try {
            $oldUrl = (string)$request->old_url;
            $prevDiskPath = null;
            if ($oldUrl && str_starts_with($oldUrl, '/storage/')) {
                $rel = ltrim(substr($oldUrl, strlen('/storage/')), '/');
                $prevDiskPath = Storage::disk('public')->path($rel);
            } elseif ($oldUrl && (str_starts_with($oldUrl, '/img/') || str_starts_with($oldUrl, 'img/'))) {
                $rel = $oldUrl[0] === '/' ? substr($oldUrl, 1) : $oldUrl;
                $candidate = public_path($rel);
                if (@is_file($candidate)) $prevDiskPath = $candidate;
            }
            if ($prevDiskPath && @is_file($prevDiskPath)) {
                $dim = @getimagesize($prevDiskPath);
                $tw = (int)($dim[0] ?? 0); $th = (int)($dim[1] ?? 0);
                if ($tw > 0 && $th > 0) {
                    $dstPath = Storage::disk('public')->path($path);
                    if ($this->gdCanProcess()) {
                        $this->gdResizeCoverTo($dstPath, $dstPath, $tw, $th);
                    }
                }
            }
        } catch (\Throwable $e) { /* ignore */ }

        $replaced = str_replace($request->old_url, $newUrl, $html);
        if ($replaced === $html) {
            // URL差異で失敗した場合、クオートやエンコードの差も考慮せずそのまま返す
            // 呼び出し側で old_url を見直す想定
            return response()->json(['success' => false, 'message' => 'Old URL not found in HTML'], 400);
        }

        $content['html'] = $replaced;
        $page->update([
            'content' => $content,
            'updated_by' => $this->editorUserId()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'HTML image replaced',
            'data' => ['new_url' => $newUrl]
        ]);
    }
}
