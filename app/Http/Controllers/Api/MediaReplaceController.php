<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaReplaceController extends Controller
{
    private function gdCanProcess(): bool
    {
        return function_exists('imagecreatetruecolor') && function_exists('imagecopyresampled');
    }

    private function gdCreateFromPath(string $path)
    {
        $info = @getimagesize($path);
        if (!$info || !isset($info[2])) return [null, null, null];
        $mime = $info['mime'] ?? '';
        $type = $info[2];
        try {
            switch ($type) {
                case IMAGETYPE_JPEG: return [imagecreatefromjpeg($path), $mime, 'jpg'];
                case IMAGETYPE_PNG: return [imagecreatefrompng($path), $mime, 'png'];
                case IMAGETYPE_WEBP: if (function_exists('imagecreatefromwebp')) return [imagecreatefromwebp($path), $mime, 'webp']; else return [null, null, null];
                case IMAGETYPE_GIF: return [imagecreatefromgif($path), $mime, 'gif'];
                default: return [null, null, null];
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
        imagealphablending($tmp, false); imagesavealpha($tmp, true);
        imagecopyresampled($tmp, $src, 0, 0, 0, 0, $nw, $nh, $sw, $sh);

        $dx = (int)max(0, ($nw - $tw) / 2);
        $dy = (int)max(0, ($nh - $th) / 2);
        $dst = imagecreatetruecolor($tw, $th);
        imagealphablending($dst, false); imagesavealpha($dst, true);
        imagecopy($dst, $tmp, 0, 0, $dx, $dy, min($tw, $nw), min($th, $nh));

        $ok = $this->gdSaveToPath($dst, $dstPath, $ext ?: pathinfo($dstPath, PATHINFO_EXTENSION));
        imagedestroy($src); imagedestroy($tmp); imagedestroy($dst);
        return $ok;
    }

    private function mapModel(string $model): ?array
    {
        $m = strtolower($model);
        $map = [
            'news' => [\App\Models\News::class, ['featured_image'], ['content']],
            'news_article' => [\App\Models\NewsArticle::class, ['featured_image'], ['content']],
            'publication' => [\App\Models\Publication::class, ['cover_image'], []],
            'economic_report' => [\App\Models\EconomicReport::class, ['cover_image'], []],
            'seminar' => [\App\Models\Seminar::class, ['featured_image'], []],
        ];
        return $map[$m] ?? null;
    }

    public function replaceModelImage(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'model' => 'required|string',
            'id' => 'required|integer',
            'field' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
        ]);

        [$class, $imageFields] = $this->mapModel($validated['model']) ?? [null, null];
        if (!$class) return response()->json(['success' => false, 'message' => 'Unsupported model'], 400);
        if (!in_array($validated['field'], $imageFields, true)) return response()->json(['success' => false, 'message' => 'Unsupported field'], 400);

        $record = $class::find($validated['id']);
        if (!$record) return response()->json(['success' => false, 'message' => 'Record not found'], 404);

        $oldUrl = (string)($record->{$validated['field']} ?? '');
        $oldPath = null;
        if ($oldUrl && str_starts_with($oldUrl, '/storage/')) {
            $rel = ltrim(substr($oldUrl, strlen('/storage/')), '/');
            $oldPath = Storage::disk('public')->path($rel);
        }

        $file = $request->file('image');
        $fileName = Str::slug($validated['model']) . '-' . $validated['id'] . '-' . Str::slug($validated['field']) . '-' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('models/' . Str::slug($validated['model']) . '/' . $validated['id'], $fileName, 'public');
        $newUrl = Storage::disk('public')->url($path);

        // Resize to match previous dimensions if possible
        try {
            if ($oldPath && @is_file($oldPath)) {
                $dim = @getimagesize($oldPath);
                $tw = (int)($dim[0] ?? 0); $th = (int)($dim[1] ?? 0);
                if ($tw > 0 && $th > 0) {
                    $dstPath = Storage::disk('public')->path($path);
                    if ($this->gdCanProcess()) {
                        $this->gdResizeCoverTo($dstPath, $dstPath, $tw, $th);
                    }
                }
            }
        } catch (\Throwable $e) {}

        $record->{$validated['field']} = $newUrl;
        if (in_array('updated_by', $record->getFillable(), true)) {
            $record->updated_by = auth()->id();
        }
        $record->save();

        return response()->json(['success' => true, 'data' => ['url' => $newUrl]]);
    }

    public function replaceModelHtmlImage(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'model' => 'required|string',
            'id' => 'required|integer',
            'field' => 'required|string', // e.g., 'content'
            'old_url' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
        ]);

        [$class, , $htmlFields] = $this->mapModel($validated['model']) ?? [null, null, null];
        if (!$class) return response()->json(['success' => false, 'message' => 'Unsupported model'], 400);
        if (!in_array($validated['field'], $htmlFields, true)) return response()->json(['success' => false, 'message' => 'Unsupported field'], 400);

        $record = $class::find($validated['id']);
        if (!$record) return response()->json(['success' => false, 'message' => 'Record not found'], 404);

        $file = $request->file('image');
        $fileName = Str::slug($validated['model']) . '-' . $validated['id'] . '-htmlimg-' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('models/' . Str::slug($validated['model']) . '/' . $validated['id'], $fileName, 'public');
        $newUrl = Storage::disk('public')->url($path);

        $html = (string)($record->{$validated['field']} ?? '');
        if ($html === '') return response()->json(['success' => false, 'message' => 'HTML content not found'], 400);

        // 旧画像の寸法に合わせて新画像をリサイズ（可能な場合）
        try {
            $oldUrl = (string)$validated['old_url'];
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

        $replaced = str_replace($validated['old_url'], $newUrl, $html);
        if ($replaced === $html) {
            return response()->json(['success' => false, 'message' => 'Old URL not found in HTML'], 400);
        }

        $record->{$validated['field']} = $replaced;
        if (in_array('updated_by', $record->getFillable(), true)) {
            $record->updated_by = auth()->id();
        }
        $record->save();

        return response()->json(['success' => true, 'data' => ['new_url' => $newUrl]]);
    }
}
