<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EconomicReport;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class EconomicReportsController extends Controller
{
    /**
     * 経済統計レポート一覧を取得
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'category' => 'nullable|string|in:quarterly,annual,regional,industry',
                'year' => 'nullable|integer|min:2000|max:2030',
                'page' => 'nullable|integer|min:1',
                'per_page' => 'nullable|integer|min:1|max:100',
                'featured_only' => 'nullable|boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'バリデーションエラー',
                    'errors' => $validator->errors()
                ], 422);
            }

            $query = EconomicReport::published()->orderBy('publication_date', 'desc');

            // カテゴリフィルタ
            if ($request->filled('category')) {
                $query->byCategory($request->category);
            }

            // 年フィルタ
            if ($request->filled('year')) {
                $query->byYear($request->year);
            }

            // 特集のみ
            if ($request->boolean('featured_only')) {
                $query->featured();
            }

            // ページネーション
            $perPage = $request->get('per_page', 12);
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
     * 特集レポートを取得
     */
    public function featured(): JsonResponse
    {
        try {
            $featured = EconomicReport::published()
                ->featured()
                ->orderBy('publication_date', 'desc')
                ->first();

            return response()->json([
                'success' => true,
                'data' => $featured
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '特集レポートの取得に失敗しました',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * レポート詳細を取得
     */
    public function show($id): JsonResponse
    {
        try {
            $report = EconomicReport::published()->findOrFail($id);

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
     * レポートをダウンロード
     */
    public function download($id): JsonResponse
    {
        try {
            $report = EconomicReport::published()->findOrFail($id);

            // 会員限定チェック（free は一般公開として扱う）
            $level = strtolower((string) ($report->membership_level ?? ''));
            $isFree = ($level === 'free');
            if ($report->members_only && !$isFree && !auth()->check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'このレポートは会員限定です。ログインしてください。',
                    'requires_login' => true
                ], 403);
            }

            // ダウンロード可能チェック
            if (!$report->is_downloadable) {
                return response()->json([
                    'success' => false,
                    'message' => 'このレポートはダウンロードできません'
                ], 403);
            }

            // ファイルの存在チェック
            if (!$report->file_url) {
                return response()->json([
                    'success' => false,
                    'message' => 'ファイルが設定されていません'
                ], 404);
            }

            // ダウンロード数を増加
            $report->increment('download_count');

            return response()->json([
                'success' => true,
                'data' => [
                    'file_url' => $report->file_url_full,
                    'filename' => basename($report->file_url),
                    'file_size' => $report->formatted_file_size
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ダウンロードに失敗しました',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * 利用可能な年のリストを取得
     */
    public function availableYears(): JsonResponse
    {
        try {
            $years = EconomicReport::published()
                ->select('year')
                ->distinct()
                ->orderBy('year', 'desc')
                ->pluck('year');

            return response()->json([
                'success' => true,
                'data' => $years
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '年の取得に失敗しました',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * カテゴリリストを取得
     */
    public function categories(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => collect(EconomicReport::CATEGORIES)->map(function ($name, $id) {
                return ['id' => $id, 'name' => $name];
            })->values()
        ]);
    }
}
