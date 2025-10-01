<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        // API リクエストの場合は null を返す（JSONエラーレスポンス）
        if ($request->is('api/*') || $request->expectsJson()) {
            return null;
        }
        
        // Web リクエストの場合のみ login ルートにリダイレクト
        return route('login');
    }
}
