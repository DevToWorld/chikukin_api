<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckFeature
{
    public function handle(Request $request, Closure $next, string $feature): Response
    {
        if (!$request->user()) {
            return response()->json([
                'success' => false,
                'message' => '認証が必要です。'
            ], 401);
        }

        if (!$request->user()->canAccessFeature($feature)) {
            return response()->json([
                'success' => false,
                'message' => 'この機能へのアクセス権限がありません。',
                'required_feature' => $feature,
                'membership_type' => $request->user()->membership_type
            ], 403);
        }

        return $next($request);
    }
}