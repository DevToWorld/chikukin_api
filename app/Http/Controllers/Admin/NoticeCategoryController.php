<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NoticeCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class NoticeCategoryController extends Controller
{
    /**
     * Common replacements for Japanese category names to English slugs.
     * Extend this map as needed.
     */
    private function slugDictionary(): array
    {
        return [
            'ニュース' => 'news',
            'お知らせ' => 'news',
            '新着' => 'news',
            'イベント' => 'event',
            '重要' => 'important',
            '重要なお知らせ' => 'important',
            'メンテナンス' => 'maintenance',
            '障害' => 'incident',
            '告知' => 'notice',
            'セミナー' => 'seminar',
            'レポート' => 'report',
            'メディア' => 'media',
        ];
    }

    /**
     * Try to make a sensible base slug from provided text using a dictionary
     * before falling back to Str::slug. Returns empty string if not possible.
     */
    private function slugifyBase(string $text): string
    {
        $text = trim($text);
        if ($text === '') return '';
        // Replace known Japanese terms with intended English words first.
        $replaced = strtr($text, $this->slugDictionary());
        $slug = Str::slug($replaced);
        return $slug; // may be empty if transliteration failed
    }

    private function makeUniqueSlug(string $base, ?int $ignoreId = null): string
    {
        $base = trim($base);
        if ($base === '') {
            $base = 'cat';
        }
        $slug = $base;
        $i = 2;
        while (
            NoticeCategory::where('slug', $slug)
                ->when($ignoreId, function ($q) use ($ignoreId) { return $q->where('id', '<>', $ignoreId); })
                ->exists()
        ) {
            $slug = $base . '-' . $i;
            $i++;
        }
        return $slug;
    }
    public function index()
    {
        return response()->json(['success'=>true, 'data'=> NoticeCategory::ordered()->get()]);
    }
    public function show($id)
    {
        return response()->json(['success'=>true, 'data'=> NoticeCategory::findOrFail($id)]);
    }
    public function store(Request $request)
    {
        $v = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:notice_categories,slug',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);
        if ($v->fails()) return response()->json(['success'=>false,'errors'=>$v->errors()], 422);
        $data = $request->only(['name','slug','sort_order','is_active']);
        // Robust slug generation: map Japanese to English keywords before slugging
        $providedSlug = isset($data['slug']) ? trim((string)$data['slug']) : '';
        $base = $this->slugifyBase($providedSlug !== '' ? $providedSlug : ($data['name'] ?? ''));
        if ($base === '') { $base = 'cat'; }
        $data['slug'] = $this->makeUniqueSlug($base);
        $item = NoticeCategory::create($data);
        return response()->json(['success'=>true,'data'=>$item], 201);
    }
    public function update(Request $request, $id)
    {
        $item = NoticeCategory::findOrFail($id);
        $v = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:notice_categories,slug,' . $item->id,
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);
        if ($v->fails()) return response()->json(['success'=>false,'errors'=>$v->errors()], 422);
        $data = $request->only(['name','slug','sort_order','is_active']);
        // If slug is provided (even empty), or if name changes without slug, regenerate using dictionary mapping
        if (array_key_exists('slug', $data)) {
            $base = $this->slugifyBase((string)($data['slug'] ?? ''));
            if ($base === '') { $base = $this->slugifyBase($data['name'] ?? $item->name); }
            if ($base === '') { $base = 'cat'; }
            $data['slug'] = $this->makeUniqueSlug($base, $item->id);
        } elseif (isset($data['name'])) {
            $base = $this->slugifyBase($data['name']);
            if ($base === '') { $base = 'cat'; }
            $data['slug'] = $this->makeUniqueSlug($base, $item->id);
        }
        $item->update($data);
        return response()->json(['success'=>true,'data'=>$item]);
    }
    public function destroy($id)
    {
        $item = NoticeCategory::findOrFail($id);
        $item->delete();
        return response()->json(['success'=>true]);
    }
}
