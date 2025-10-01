<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FinancialReport;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FinancialReportsController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = FinancialReport::published();

        if ($request->has('company')) {
            $query->byCompany($request->company);
        }

        if ($request->has('year')) {
            $query->byYear($request->year);
        }

        if ($request->has('report_type')) {
            $query->where('report_type', $request->report_type);
        }

        if ($request->boolean('audited_only')) {
            $query->audited();
        }

        $perPage = $request->get('per_page', 15);
        $reports = $query->orderBy('report_date', 'desc')->paginate($perPage);

        return response()->json($reports);
    }

    public function show($id): JsonResponse
    {
        $report = FinancialReport::published()->findOrFail($id);
        return response()->json($report);
    }

    public function companies(): JsonResponse
    {
        $companies = FinancialReport::published()
            ->distinct()
            ->pluck('company_name')
            ->sort()
            ->values();
        
        return response()->json($companies);
    }

    public function latest(Request $request): JsonResponse
    {
        $limit = $request->get('limit', 5);
        
        $reports = FinancialReport::published()
            ->orderBy('report_date', 'desc')
            ->limit($limit)
            ->get();
        
        return response()->json($reports);
    }

    public function byCompany($companyName): JsonResponse
    {
        $reports = FinancialReport::published()
            ->byCompany($companyName)
            ->orderBy('report_date', 'desc')
            ->get();
        
        return response()->json($reports);
    }
}