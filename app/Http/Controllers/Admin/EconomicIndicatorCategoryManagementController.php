<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EconomicIndicatorCategory;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class EconomicIndicatorCategoryManagementController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $categories = EconomicIndicatorCategory::query()->ordered()->get();
        return response()->json(['success' => true, 'data' => $categories]);
    }

    public function show($id): JsonResponse
    {
        $category = EconomicIndicatorCategory::findOrFail($id);
        return response()->json(['success' => true, 'data' => $category]);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:economic_indicator_categories,slug',
            'description' => 'nullable|string',
            'color_code' => 'nullable|string|max:20',
            'icon' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $data = $request->only(['name','slug','description','color_code','icon','is_active','sort_order']);
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }
        $category = EconomicIndicatorCategory::create($data);
        return response()->json(['success' => true, 'data' => $category], 201);
    }

    public function update($id, Request $request): JsonResponse
    {
        $category = EconomicIndicatorCategory::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:economic_indicator_categories,slug,' . $category->id,
            'description' => 'nullable|string',
            'color_code' => 'nullable|string|max:20',
            'icon' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $data = $request->only(['name','slug','description','color_code','icon','is_active','sort_order']);
        if (isset($data['name']) && empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }
        $category->update($data);
        return response()->json(['success' => true, 'data' => $category]);
    }

    public function destroy($id): JsonResponse
    {
        $category = EconomicIndicatorCategory::findOrFail($id);
        // NOTE: 関連指標がある場合は削除不可にする場合は以下で判定
        // if ($category->indicators()->exists()) { return response()->json(['success' => false, 'message' => '関連する指標が存在します'], 422); }
        $category->delete();
        return response()->json(['success' => true]);
    }
}

