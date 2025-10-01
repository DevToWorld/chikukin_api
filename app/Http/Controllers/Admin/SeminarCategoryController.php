<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SeminarCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SeminarCategoryController extends Controller
{
    public function index()
    {
        $items = SeminarCategory::ordered()->get();
        return response()->json(['success' => true, 'data' => $items]);
    }

    public function show($id)
    {
        $item = SeminarCategory::findOrFail($id);
        return response()->json(['success' => true, 'data' => $item]);
    }

    public function store(Request $request)
    {
        $v = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:seminar_categories,slug',
            'color_code' => 'nullable|string|max:20',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);
        if ($v->fails()) return response()->json(['success'=>false,'errors'=>$v->errors()], 422);

        $data = $request->only(['name','slug','color_code','sort_order','is_active']);
        if (empty($data['slug'])) $data['slug'] = Str::slug($data['name']);
        $item = SeminarCategory::create($data);
        return response()->json(['success'=>true,'data'=>$item], 201);
    }

    public function update(Request $request, $id)
    {
        $item = SeminarCategory::findOrFail($id);
        $v = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:seminar_categories,slug,' . $item->id,
            'color_code' => 'nullable|string|max:20',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);
        if ($v->fails()) return response()->json(['success'=>false,'errors'=>$v->errors()], 422);

        $data = $request->only(['name','slug','color_code','sort_order','is_active']);
        if (isset($data['name']) && empty($data['slug'])) $data['slug'] = Str::slug($data['name']);
        $item->update($data);
        return response()->json(['success'=>true,'data'=>$item]);
    }

    public function destroy($id)
    {
        $item = SeminarCategory::findOrFail($id);
        // 関連セミナーが存在する場合は削除禁止にするなら以下をコメントアウト解除
        // if ($item->seminars()->exists()) return response()->json(['success'=>false,'message'=>'関連するセミナーがあります'], 422);
        $item->delete();
        return response()->json(['success'=>true]);
    }
}

