<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            $perMinute = (int) env('API_RATE_LIMIT_PER_MIN', 60);
            $id = $request->user()?->id ?: $request->ip();
            return Limit::perMinute($perMinute)->by($id);
        });

        // Public content pages: allow a higher limit than generic API
        RateLimiter::for('public-pages', function (Request $request) {
            $perMinute = (int) env('PUBLIC_PAGES_RATE_PER_MIN', 600);
            return Limit::perMinute($perMinute)->by($request->ip());
        });

        // Admin login throttle (env-driven)
        RateLimiter::for('admin-login', function (Request $request) {
            $maxAttempts = (int) env('ADMIN_LOGIN_MAX_ATTEMPTS', 5);
            $decaySeconds = (int) env('ADMIN_LOGIN_DECAY_SECONDS', 60);
            $decayMinutes = max(1, (int) ceil($decaySeconds / 60));
            $keyBase = strtolower((string) $request->input('email')) ?: 'unknown';
            $key = sprintf('admin-login:%s|%s', $keyBase, $request->ip());
            return [ Limit::perMinutes($decayMinutes, $maxAttempts)->by($key) ];
        });

        // Admin CMS named throttles (env-driven)
        RateLimiter::for('admin-cms', function (Request $request) {
            $ipWhitelist = array_filter(array_map('trim', explode(',', (string) env('ADMIN_RATE_IP_WHITELIST', ''))));
            if (in_array($request->ip(), $ipWhitelist, true)) {
                return Limit::none();
            }
            $perMinute = (int) env('ADMIN_CMS_RATE_PER_MIN', 60); // default keeps current behavior
            return Limit::perMinute($perMinute)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('admin-publish', function (Request $request) {
            $ipWhitelist = array_filter(array_map('trim', explode(',', (string) env('ADMIN_RATE_IP_WHITELIST', ''))));
            if (in_array($request->ip(), $ipWhitelist, true)) {
                return Limit::none();
            }
            $perMinute = (int) env('ADMIN_PUBLISH_RATE_PER_MIN', 10);
            return Limit::perMinute($perMinute)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('admin-media', function (Request $request) {
            $ipWhitelist = array_filter(array_map('trim', explode(',', (string) env('ADMIN_RATE_IP_WHITELIST', ''))));
            if (in_array($request->ip(), $ipWhitelist, true)) {
                return Limit::none();
            }
            $perMinute = (int) env('ADMIN_MEDIA_RATE_PER_MIN', 30);
            return Limit::perMinute($perMinute)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('admin-overrides', function (Request $request) {
            $ipWhitelist = array_filter(array_map('trim', explode(',', (string) env('ADMIN_RATE_IP_WHITELIST', ''))));
            if (in_array($request->ip(), $ipWhitelist, true)) {
                return Limit::none();
            }
            $perMinute = (int) env('ADMIN_OVERRIDES_RATE_PER_MIN', 20);
            return Limit::perMinute($perMinute)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('admin-preview', function (Request $request) {
            $ipWhitelist = array_filter(array_map('trim', explode(',', (string) env('ADMIN_RATE_IP_WHITELIST', ''))));
            if (in_array($request->ip(), $ipWhitelist, true)) {
                return Limit::none();
            }
            $perMinute = (int) env('ADMIN_PREVIEW_RATE_PER_MIN', 30);
            return Limit::perMinute($perMinute)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
