<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EconomicIndicator;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class EconomicIndicatorManagementController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'search' => 'nullable|string|max:255',
            'category' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $query = EconomicIndicator::query()->orderBy('sort_order')->orderBy('name');

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('source', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->byCategory($request->get('category'));
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', filter_var($request->get('is_active'), FILTER_VALIDATE_BOOLEAN));
        }

        $perPage = $request->get('per_page', 20);
        $indicators = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => [
                'indicators' => $indicators->items(),
                'pagination' => [
                    'current_page' => $indicators->currentPage(),
                    'total_pages' => $indicators->lastPage(),
                    'per_page' => $indicators->perPage(),
                    'total' => $indicators->total(),
                ]
            ]
        ]);
    }

    public function show($id): JsonResponse
    {
        $indicator = EconomicIndicator::findOrFail($id);
        return response()->json(['success' => true, 'data' => $indicator]);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'source' => 'nullable|string|max:255',
            'frequency' => 'nullable|string|in:daily,monthly,quarterly,annual',
            'unit' => 'nullable|string|max:50',
            'link_url' => 'nullable|url|max:2048',
            'publication_date' => 'nullable|date_format:Y-m-d',
            'metadata' => 'nullable|array',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $data = $request->only([
            'name','category','description','source','frequency','unit','link_url','publication_date','metadata','is_active','sort_order'
        ]);
        $indicator = EconomicIndicator::create($data);
        return response()->json(['success' => true, 'data' => $indicator], 201);
    }

    public function update($id, Request $request): JsonResponse
    {
        $indicator = EconomicIndicator::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'category' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'source' => 'nullable|string|max:255',
            'frequency' => 'nullable|string|in:daily,monthly,quarterly,annual',
            'unit' => 'nullable|string|max:50',
            'link_url' => 'nullable|url|max:2048',
            'publication_date' => 'nullable|date_format:Y-m-d',
            'metadata' => 'nullable|array',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $indicator->update($request->only([
            'name','category','description','source','frequency','unit','link_url','publication_date','metadata','is_active','sort_order'
        ]));

        return response()->json(['success' => true, 'data' => $indicator]);
    }

    public function destroy($id): JsonResponse
    {
        $indicator = EconomicIndicator::findOrFail($id);
        $indicator->delete();
        return response()->json(['success' => true]);
    }
}
