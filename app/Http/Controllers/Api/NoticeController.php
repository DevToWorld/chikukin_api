<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NewsArticle;
use App\Models\NoticeCategory;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class NoticeController extends Controller
{
    /**
     * お知らせ一覧を取得
     */
    public function index(Request $request): JsonResponse
    {
        $query = NewsArticle::where('type', 'notice');

        // 検索フィルター
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('content', 'LIKE', "%{$search}%")
                  ->orWhere('summary', 'LIKE', "%{$search}%");
            });
        }

        // ステータスフィルター
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // カテゴリフィルター
        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }

        // ソート
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);

        // ページネーション
        $perPage = $request->get('per_page', 15);
        $notices = $query->paginate($perPage);

        return response()->json($notices);
    }

    /**
     * お知らせ詳細を取得
     */
    public function show($id): JsonResponse
    {
        $notice = NewsArticle::where('type', 'notice')->findOrFail($id);
        return response()->json($notice);
    }

    /**
     * 新規お知らせを作成
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'summary' => 'nullable|string|max:500',
            'category' => 'required|string|max:100',
            'status' => 'required|in:draft,published,archived',
            'published_at' => 'nullable|date',
            'featured' => 'boolean',


            'featured_image' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'バリデーションエラー',
                'errors' => $validator->errors()
            ], 422);
        }

        $notice = NewsArticle::create([
            'title' => $request->title,
            'slug' => $this->generateUniqueSlug($request->title),
            'content' => $request->content,
            'summary' => $request->summary,
            // お知らせとして type=notice を固定し、category はサブカテゴリとして保存
            'type' => 'notice',
            'category' => $request->category,
            'is_published' => $request->status === 'published',
            'published_at' => $request->published_at ?? ($request->status === 'published' ? now() : null),
            'is_featured' => $request->featured ?? false,
            'featured_image' => $request->featured_image,
        ]);

        return response()->json([
            'message' => 'お知らせを作成しました',
            'notice' => $notice
        ], 201);
    }

    /**
     * タイトルからユニークなスラッグを生成
     */
    private function generateUniqueSlug(string $title): string
    {
        $base = Str::slug($title);

        // 日本語などで slug が空になる場合のフォールバック
        if (empty($base)) {
            $base = 'notice';
        }

        $slug = $base;
        $suffix = 2;

        while (\App\Models\NewsArticle::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $suffix;
            $suffix++;
        }

        return $slug;
    }

    /**
     * お知らせを更新
     */
    public function update(Request $request, $id): JsonResponse
    {
        $notice = NewsArticle::where('type', 'notice')->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'summary' => 'nullable|string|max:500',
            'category' => 'required|string|max:100',
            'status' => 'required|in:draft,published,archived',
            'published_at' => 'nullable|date',
            'featured' => 'boolean',


            'featured_image' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'バリデーションエラー',
                'errors' => $validator->errors()
            ], 422);
        }

        $updateData = [
            'title' => $request->title,
            'content' => $request->content,
            'summary' => $request->summary,
            'category' => $request->category,
            // is_published/published_at を NewsArticle のスキーマに合わせて更新
            'is_published' => $request->status === 'published',
            'published_at' => $request->published_at,
            'is_featured' => $request->featured ?? false,
            'featured_image' => $request->featured_image,
        ];

        // 公開状態に変更された場合は公開日時を設定
        if ($request->status === 'published' && !$notice->published_at) {
            $updateData['published_at'] = now();
        }

        $notice->update($updateData);

        return response()->json([
            'message' => 'お知らせを更新しました',
            'notice' => $notice
        ]);
    }

    /**
     * お知らせを削除
     */
    public function destroy($id): JsonResponse
    {
        $notice = NewsArticle::where('type', 'notice')->findOrFail($id);
        $notice->delete();

        return response()->json([
            'message' => 'お知らせを削除しました'
        ]);
    }

    /**
     * お知らせ統計を取得
     */
    public function stats(): JsonResponse
    {
        $stats = [
            'total_notices' => NewsArticle::where('type', 'notice')->count(),
            'published_notices' => NewsArticle::where('type', 'notice')->where('is_published', true)->count(),
            'draft_notices' => NewsArticle::where('type', 'notice')->where('is_published', false)->count(),
            'featured_notices' => NewsArticle::where('type', 'notice')->where('is_featured', true)->count(),
            'recent_notices' => NewsArticle::where('type', 'notice')->where('created_at', '>=', now()->subDays(30))->count(),
        ];

        return response()->json($stats);
    }

    /**
     * お知らせステータスを更新
     */
    public function updateStatus(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:draft,published,archived',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'バリデーションエラー',
                'errors' => $validator->errors()
            ], 422);
        }

        $notice = NewsArticle::where('category', 'notice')->findOrFail($id);
        
        $updateData = ['status' => $request->status];
        
        // 公開状態に変更された場合は公開日時を設定
        if ($request->status === 'published' && !$notice->published_date) {
            $updateData['published_date'] = now()->format('Y-m-d');
        }
        
        $notice->update($updateData);

        return response()->json([
            'message' => 'お知らせステータスを更新しました',
            'notice' => $notice
        ]);
    }

    /**
     * カテゴリ一覧を取得
     */
    public function categories(): JsonResponse
    {
        // まず NewsArticle 側（実データ）から使用中のカテゴリslugを取得
        $slugs = NewsArticle::where('type', 'notice')
            ->distinct()
            ->pluck('category')
            ->filter()
            ->values()
            ->all();

        // NoticeCategory にある表示名と突き合わせ（is_activeのみ公開）
        $list = [];
        if (!empty($slugs)) {
            $records = NoticeCategory::whereIn('slug', $slugs)
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get(['slug','name','sort_order','is_active']);

            $found = [];
            foreach ($records as $rec) {
                $list[] = [ 'slug' => $rec->slug, 'name' => $rec->name ];
                $found[$rec->slug] = true;
            }
            // カテゴリマスタに無いslugもフォールバックで提示（name=slug）
            foreach ($slugs as $slug) {
                if ($slug && !isset($found[$slug])) {
                    $list[] = [ 'slug' => $slug, 'name' => $slug ];
                }
            }
        }

        return response()->json($list);
    }
}
