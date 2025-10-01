<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EconomicStatistic;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class EconomicStatisticsController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = EconomicStatistic::published();

        if ($request->has('category')) {
            $query->byCategory($request->category);
        }

        if ($request->has('period_start') && $request->has('period_end')) {
            $query->inPeriod($request->period_start, $request->period_end);
        }

        $perPage = $request->get('per_page', 15);
        $statistics = $query->orderBy('period_end', 'desc')->paginate($perPage);

        return response()->json($statistics);
    }

    public function show($id): JsonResponse
    {
        $statistic = EconomicStatistic::published()->findOrFail($id);
        return response()->json($statistic);
    }

    public function categories(): JsonResponse
    {
        $categories = EconomicStatistic::published()
            ->distinct()
            ->pluck('category');
        
        return response()->json($categories);
    }

    public function latest(Request $request): JsonResponse
    {
        $limit = $request->get('limit', 5);
        
        $statistics = EconomicStatistic::published()
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
        
        return response()->json($statistics);
    }
}