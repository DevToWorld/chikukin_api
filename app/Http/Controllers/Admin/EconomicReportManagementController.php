<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EconomicReport;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EconomicReportManagementController extends Controller
{
    /**
     * 経済統計レポート一覧を取得
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'search' => 'nullable|string|max:255',
                'category' => 'nullable|string|in:quarterly,annual,regional,industry',
                'year' => 'nullable|integer|min:2000|max:2030',
                'status' => 'nullable|string|in:published,draft',
                'page' => 'nullable|integer|min:1',
                'per_page' => 'nullable|integer|min:1|max:100'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'バリデーションエラー',
                    'errors' => $validator->errors()
                ], 422);
            }

            $query = EconomicReport::query()->orderBy('created_at', 'desc');

            // 検索
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', '%' . $search . '%')
                      ->orWhere('description', 'like', '%' . $search . '%')
                      ->orWhere('keywords', 'like', '%' . $search . '%');
                });
            }

            // カテゴリフィルタ
            if ($request->filled('category')) {
                $query->byCategory($request->category);
            }

            // 年フィルタ
            if ($request->filled('year')) {
                $query->byYear($request->year);
            }

            // ステータスフィルタ
            if ($request->filled('status')) {
                if ($request->status === 'published') {
                    $query->where('is_published', true);
                } else {
                    $query->where('is_published', false);
                }
            }

            // ページネーション
            $perPage = $request->get('per_page', 20);
            $reports = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => [
                    'reports' => $reports->items(),
                    'pagination' => [
                        'current_page' => $reports->currentPage(),
                        'total_pages' => $reports->lastPage(),
                        'per_page' => $reports->perPage(),
                        'total' => $reports->total()
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '経済統計レポートの取得に失敗しました',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * 経済統計レポートの詳細を取得
     */
    public function show($id): JsonResponse
    {
        try {
            $report = EconomicReport::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $report
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'レポートが見つかりませんでした',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 404);
        }
    }

    /**
     * 新しい経済統計レポートを作成
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'category' => 'required|string|in:quarterly,annual,regional,industry',
                'year' => 'required|integer|min:2000|max:2100',
                'publication_date' => 'required|date_format:Y-m-d',
                'author' => 'nullable|string|max:255',
                'publisher' => 'nullable|string|max:255',
                'keywords' => 'nullable|string',
                'pages' => 'nullable|integer|min:0',
                'is_downloadable' => 'boolean',
                'members_only' => 'boolean',
                'membership_level' => 'nullable|string|in:free,standard,premium',
                'is_featured' => 'boolean',
                'is_published' => 'boolean',
                'sort_order' => 'nullable|integer',
                'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'file' => 'nullable|file|mimes:pdf,doc,docx|max:10240'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'バリデーションエラー',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $request->only([
                'title', 'description', 'category', 'year', 'publication_date',
                'author', 'publisher', 'keywords', 'pages',
                'is_downloadable', 'members_only', 'membership_level', 'is_featured', 'is_published',
                'sort_order'
            ]);

            // デフォルト値の設定
            $data['author'] = $data['author'] ?? 'ちくぎん地域経済研究所';
            $data['publisher'] = $data['publisher'] ?? '株式会社ちくぎん地域経済研究所';
            $data['is_downloadable'] = $request->boolean('is_downloadable', true);
            // members_only は明示指定を優先、無ければ membership_level から補完
            if ($request->has('members_only')) {
                $data['members_only'] = (bool) $request->boolean('members_only');
            } elseif ($request->has('membership_level')) {
                $lvl = strtolower($request->input('membership_level'));
                $data['members_only'] = $lvl !== 'free';
            } else {
                $data['members_only'] = $request->boolean('members_only', true);
            }
            $data['is_featured'] = $request->boolean('is_featured');
            $data['is_published'] = $request->boolean('is_published');
            // 互換：members_onlyからmembership_levelを補完（明示指定が無い場合）
            if (!isset($data['membership_level'])) {
                $data['membership_level'] = $data['members_only'] ? 'standard' : 'free';
            }
            // 明示的に members_only=false の場合は membership_level を 'free' に正規化
            if (array_key_exists('members_only', $data) && $data['members_only'] === false) {
                $data['membership_level'] = 'free';
            }

            // カバー画像のアップロード
            if ($request->hasFile('cover_image')) {
                $coverImage = $request->file('cover_image');
                $coverImagePath = $coverImage->store('economic-reports/covers', 'public');
                $data['cover_image'] = $coverImagePath;
            }

            // ファイルのアップロード
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = Str::slug($data['title']) . '_' . time() . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('economic-reports/files', $fileName, 'public');
                $data['file_url'] = $filePath;
                // バイト数を専用カラムに保存（10MB超でも安全）
                $data['file_size_bytes'] = $file->getSize();
            }

            $report = EconomicReport::create($data);

            return response()->json([
                'success' => true,
                'message' => '経済統計レポートが作成されました',
                'data' => $report
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '経済統計レポートの作成に失敗しました',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * 経済統計レポートを更新
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $report = EconomicReport::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'category' => 'required|string|in:quarterly,annual,regional,industry',
                'year' => 'required|integer|min:2000|max:2100',
                'publication_date' => 'required|date_format:Y-m-d',
                'author' => 'nullable|string|max:255',
                'publisher' => 'nullable|string|max:255',
                'keywords' => 'nullable|string',
                'pages' => 'nullable|integer|min:0',
                'is_downloadable' => 'boolean',
                'members_only' => 'boolean',
                'membership_level' => 'nullable|string|in:free,standard,premium',
                'is_featured' => 'boolean',
                'is_published' => 'boolean',
                'sort_order' => 'nullable|integer',
                'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'file' => 'nullable|file|mimes:pdf,doc,docx|max:10240'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'バリデーションエラー',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $request->only([
                'title', 'description', 'category', 'year', 'publication_date',
                'author', 'publisher', 'keywords', 'pages',
                'is_downloadable', 'members_only', 'membership_level', 'is_featured', 'is_published',
                'sort_order'
            ]);

            $data['is_downloadable'] = $request->boolean('is_downloadable', true);
            if ($request->has('members_only')) {
                $data['members_only'] = (bool) $request->boolean('members_only');
            } elseif ($request->has('membership_level')) {
                $lvl = strtolower($request->input('membership_level'));
                $data['members_only'] = $lvl !== 'free';
            } else {
                $data['members_only'] = $request->boolean('members_only', true);
            }
            $data['is_featured'] = $request->boolean('is_featured');
            $data['is_published'] = $request->boolean('is_published');
            if (!isset($data['membership_level']) && $request->has('members_only')) {
                $data['membership_level'] = $request->boolean('members_only') ? 'standard' : 'free';
            }
            if (array_key_exists('members_only', $data) && $data['members_only'] === false) {
                $data['membership_level'] = 'free';
            }

            // カバー画像の更新
            if ($request->hasFile('cover_image')) {
                // 既存画像を削除
                if ($report->cover_image) {
                    Storage::disk('public')->delete($report->cover_image);
                }
                
                $coverImage = $request->file('cover_image');
                $coverImagePath = $coverImage->store('economic-reports/covers', 'public');
                $data['cover_image'] = $coverImagePath;
            }

            // ファイルの更新
            if ($request->hasFile('file')) {
                // 既存ファイルを削除
                if ($report->file_url) {
                    Storage::disk('public')->delete($report->file_url);
                }
                
                $file = $request->file('file');
                $fileName = Str::slug($data['title']) . '_' . time() . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('economic-reports/files', $fileName, 'public');
                $data['file_url'] = $filePath;
                $data['file_size_bytes'] = $file->getSize();
            }

            $report->update($data);

            return response()->json([
                'success' => true,
                'message' => '経済統計レポートが更新されました',
                'data' => $report->fresh()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '経済統計レポートの更新に失敗しました',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * 経済統計レポートを削除
     */
    public function destroy($id): JsonResponse
    {
        try {
            $report = EconomicReport::findOrFail($id);

            // 関連ファイルを削除
            if ($report->cover_image) {
                Storage::disk('public')->delete($report->cover_image);
            }
            
            if ($report->file_url) {
                Storage::disk('public')->delete($report->file_url);
            }

            $report->delete();

            return response()->json([
                'success' => true,
                'message' => '経済統計レポートが削除されました'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '経済統計レポートの削除に失敗しました',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * 公開ステータスを切り替え
     */
    public function togglePublishStatus(Request $request, $id): JsonResponse
    {
        try {
            $report = EconomicReport::findOrFail($id);
            $report->is_published = !$report->is_published;
            $report->save();

            return response()->json([
                'success' => true,
                'message' => '公開ステータスが更新されました',
                'data' => ['is_published' => $report->is_published]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '公開ステータスの更新に失敗しました',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * 特集ステータスを切り替え
     */
    public function toggleFeaturedStatus(Request $request, $id): JsonResponse
    {
        try {
            $report = EconomicReport::findOrFail($id);
            $report->is_featured = !$report->is_featured;
            $report->save();

            return response()->json([
                'success' => true,
                'message' => '特集ステータスが更新されました',
                'data' => ['is_featured' => $report->is_featured]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '特集ステータスの更新に失敗しました',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * 統計情報を取得
     */
    public function statistics(): JsonResponse
    {
        try {
            $stats = [
                'total' => EconomicReport::count(),
                'published' => EconomicReport::where('is_published', true)->count(),
                'draft' => EconomicReport::where('is_published', false)->count(),
                'featured' => EconomicReport::where('is_featured', true)->count(),
                'by_category' => EconomicReport::selectRaw('category, count(*) as count')
                    ->groupBy('category')
                    ->pluck('count', 'category'),
                'by_year' => EconomicReport::selectRaw('year, count(*) as count')
                    ->groupBy('year')
                    ->orderBy('year', 'desc')
                    ->pluck('count', 'year'),
                'total_downloads' => EconomicReport::sum('download_count')
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '統計情報の取得に失敗しました',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}
