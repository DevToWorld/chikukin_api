<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Sanctumトークンを使って認証されたユーザーを取得
        $user = auth('sanctum')->user();
        
        // ユーザーが存在し、Adminインスタンスであることを確認
        if (!$user || !($user instanceof \App\Models\Admin)) {
            return response()->json(['message' => 'Unauthorized - Admin access required'], 403);
        }
        
        // is_adminプロパティまたはメソッドをチェック
        $isAdmin = false;
        if (method_exists($user, 'isAdmin')) {
            $isAdmin = $user->isAdmin();
        } elseif (property_exists($user, 'is_admin')) {
            $isAdmin = $user->is_admin;
        } elseif (isset($user->is_admin)) {
            $isAdmin = $user->is_admin;
        }
        
        if (!$isAdmin) {
            return response()->json(['message' => 'Unauthorized - Admin privileges required'], 403);
        }

        return $next($request);
    }
}