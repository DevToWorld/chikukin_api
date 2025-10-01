<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EconomicIndicator;
use App\Models\EconomicIndicatorCategory;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class EconomicIndicatorsController extends Controller
{
    // 公開API: カテゴリー一覧（アクティブのみ）
    public function categories(): JsonResponse
    {
        $categories = EconomicIndicatorCategory::active()
            ->ordered()
            ->get()
            ->map(function ($cat) {
                return [
                    'id' => $cat->id,
                    'name' => $cat->name,
                    'slug' => $cat->slug,
                    'description' => $cat->description,
                    'color_code' => $cat->color_code,
                    'icon' => $cat->icon,
                    'sort_order' => $cat->sort_order,
                    'indicator_count' => $cat->indicator_count,
                ];
            });

        return response()->json(['success' => true, 'data' => $categories])
            ->header('Cache-Control', 'public, max-age=3600, stale-while-revalidate=86400');
    }

    // 公開API: 指標一覧（カテゴリーで絞り込み可能）
    public function index(Request $request): JsonResponse
    {
        $query = EconomicIndicator::query()->active()->ordered();

        if ($request->filled('category')) {
            $query->byCategory($request->get('category'));
        }

        $indicators = $query->get()->map(function ($i) {
            return [
                'id' => $i->id,
                'name' => $i->name,
                'category' => $i->category,
                'description' => $i->description,
                'source' => $i->source,
                'frequency' => $i->frequency,
                'unit' => $i->unit,
                'link_url' => $i->link_url,
                'publication_date' => optional($i->publication_date)->format('Y-m-d'),
                'publication_schedule' => is_array($i->metadata ?? null) ? ($i->metadata['publication_schedule'] ?? null) : null,
                'sort_order' => $i->sort_order,
            ];
        });

        return response()->json(['success' => true, 'data' => $indicators])
            ->header('Cache-Control', 'public, max-age=60, stale-while-revalidate=300');
    }
}
