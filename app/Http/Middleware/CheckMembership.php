<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMembership
{
    public function handle(Request $request, Closure $next, string ...$memberships): Response
    {
        if (!$request->user()) {
            return response()->json([
                'success' => false,
                'message' => '認証が必要です。'
            ], 401);
        }

        if (!$request->user()->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'アカウントが無効になっています。'
            ], 403);
        }

        if (!$request->user()->membershipIsActive()) {
            return response()->json([
                'success' => false,
                'message' => '会員資格の有効期限が切れています。'
            ], 403);
        }

        if (empty($memberships)) {
            return $next($request);
        }

        $userMembership = $request->user()->membership_type;

        if (in_array('premium', $memberships) && !$request->user()->hasPremiumAccess()) {
            return response()->json([
                'success' => false,
                'message' => 'プレミアム会員限定のコンテンツです。',
                'required_membership' => 'premium',
                'current_membership' => $userMembership
            ], 403);
        }

        if (in_array('standard', $memberships) && !$request->user()->hasStandardAccess()) {
            return response()->json([
                'success' => false,
                'message' => 'スタンダード会員以上の会員限定のコンテンツです。',
                'required_membership' => 'standard',
                'current_membership' => $userMembership
            ], 403);
        }

        if (!in_array($userMembership, $memberships)) {
            return response()->json([
                'success' => false,
                'message' => 'このコンテンツへのアクセス権限がありません。',
                'required_membership' => $memberships,
                'current_membership' => $userMembership
            ], 403);
        }

        return $next($request);
    }
}