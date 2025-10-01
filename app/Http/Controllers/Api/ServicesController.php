<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ServicesController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Service::active();

        if ($request->boolean('featured_only')) {
            $query->featured();
        }

        $services = $query->ordered()->get();

        return response()->json($services);
    }

    public function show($slug): JsonResponse
    {
        $service = Service::active()
            ->where('slug', $slug)
            ->firstOrFail();
        
        return response()->json($service);
    }

    public function featured(): JsonResponse
    {
        $services = Service::active()
            ->featured()
            ->ordered()
            ->get();
        
        return response()->json($services);
    }
}