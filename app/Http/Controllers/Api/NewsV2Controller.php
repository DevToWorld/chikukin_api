<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NewsV2Controller extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            $query = News::published()->orderBy('published_date', 'desc');

            // フィルタリング
            if ($request->has('category')) {
                $query->byCategory($request->category);
            }

            if ($request->boolean('featured_only')) {
                $query->featured();
            }

            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('excerpt', 'like', "%{$search}%")
                      ->orWhere('content', 'like', "%{$search}%");
                });
            }

            // 会員レベルフィルタリング
            $membershipLevel = $request->get('membership_level', 'none');
            $query->forMembership($membershipLevel);

            // ページネーション
            $perPage = $request->get('per_page', 10);
            $news = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => [
                    'news' => $news->items(),
                    'pagination' => [
                        'current_page' => $news->currentPage(),
                        'total_pages' => $news->lastPage(),
                        'total_items' => $news->total(),
                        'per_page' => $news->perPage()
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'お知らせの取得に失敗しました。',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $news = News::published()
                ->findOrFail($id);

            // ビュー数をインクリメント
            $news->increment('view_count');

            return response()->json([
                'success' => true,
                'data' => [
                    'news' => $news,
                    'formatted_date' => $news->formatted_date
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'お知らせが見つかりません。',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'content' => 'nullable|string',
                'category' => 'required|in:seminar,publication,notice,research,general',
                'type' => 'required|in:news,seminar,publication,notice',
                'published_date' => 'required|date',
                'membership_requirement' => 'required|in:none,basic,standard,premium',
                'featured_image' => 'nullable|string',
                'is_featured' => 'boolean'
            ]);

            $news = News::create([
                ...$validatedData,
                'created_by' => auth()->id(),
                'status' => 'published'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'お知らせが作成されました。',
                'data' => ['news' => $news]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'お知らせの作成に失敗しました。',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        try {
            $news = News::findOrFail($id);

            $validatedData = $request->validate([
                'title' => 'sometimes|required|string|max:255',
                'description' => 'sometimes|required|string',
                'content' => 'nullable|string',
                'category' => 'sometimes|required|in:seminar,publication,notice,research,general',
                'type' => 'sometimes|required|in:news,seminar,publication,notice',
                'published_date' => 'sometimes|required|date',
                'membership_requirement' => 'sometimes|required|in:none,basic,standard,premium',
                'status' => 'sometimes|required|in:draft,published,archived',
                'featured_image' => 'nullable|string',
                'is_featured' => 'boolean'
            ]);

            $news->update([
                ...$validatedData,
                'updated_by' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'お知らせが更新されました。',
                'data' => ['news' => $news]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'お知らせの更新に失敗しました。',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $news = News::findOrFail($id);
            $news->delete();

            return response()->json([
                'success' => true,
                'message' => 'お知らせが削除されました。'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'お知らせの削除に失敗しました。',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
