<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PublicationCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PublicationCategoryController extends Controller
{
    public function index()
    {
        return response()->json(['success'=>true, 'data'=> PublicationCategory::ordered()->get()]);
    }
    public function show($id)
    {
        return response()->json(['success'=>true, 'data'=> PublicationCategory::findOrFail($id)]);
    }
    public function store(Request $request)
    {
        // 先に最終的な slug を決定してからバリデーション（重複を422に）
        $name = $request->input('name');
        $slug = $request->input('slug');
        if (!$slug && $name) { $slug = Str::slug($name); }
        // 日本語などで slug が生成できないケースのフォールバック
        if (!$slug) { $slug = 'cat-' . substr(md5(($name ?? '') . microtime(true)), 0, 8); }

        $payload = [
            'name' => $name,
            'slug' => $slug,
            'sort_order' => $request->input('sort_order'),
            'is_active' => $request->input('is_active'),
        ];

        $v = Validator::make($payload, [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:publication_categories,slug',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);
        if ($v->fails()) return response()->json(['success'=>false,'errors'=>$v->errors()], 422);

        $item = PublicationCategory::create($payload);
        return response()->json(['success'=>true,'data'=>$item], 201);
    }
    public function update(Request $request, $id)
    {
        $item = PublicationCategory::findOrFail($id);
        // name の変更に伴い slug 未指定なら自動生成し、その値で重複チェック
        $name = $request->has('name') ? $request->input('name') : $item->name;
        $slug = $request->has('slug') ? $request->input('slug') : null;
        if ($slug === null && $request->has('name')) { $slug = Str::slug($name); }
        if ($slug === null) { $slug = $item->slug; }
        if (!$slug) { $slug = 'cat-' . substr(md5(($name ?? '') . microtime(true)), 0, 8); }

        $payload = [
            'name' => $name,
            'slug' => $slug,
            'sort_order' => $request->input('sort_order', $item->sort_order),
            'is_active' => $request->input('is_active', $item->is_active),
        ];

        $v = Validator::make($payload, [
            'name' => 'sometimes|required|string|max:255',
            'slug' => 'required|string|max:255|unique:publication_categories,slug,' . $item->id,
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);
        if ($v->fails()) return response()->json(['success'=>false,'errors'=>$v->errors()], 422);

        $item->update($payload);
        return response()->json(['success'=>true,'data'=>$item]);
    }
    public function destroy($id)
    {
        $item = PublicationCategory::findOrFail($id);
        // if ($item->publications()->exists()) return response()->json(['success'=>false,'message'=>'関連する刊行物があります'], 422);
        $item->delete();
        return response()->json(['success'=>true]);
    }
}
