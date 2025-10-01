<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ForceCors
{
    public function handle(Request $request, Closure $next)
    {
        $origin = (string) $request->headers->get('Origin', '');
        $config = config('cors');

        $allowedOrigin = $this->matchOrigin($origin, $config);

        // Preflight shortcut
        if ($request->getMethod() === 'OPTIONS') {
            $response = response()->noContent(204);
            if ($allowedOrigin) {
                $this->applyHeaders($response, $allowedOrigin, $config);
            }
            return $response;
        }

        $response = $next($request);
        if ($allowedOrigin) {
            $this->applyHeaders($response, $allowedOrigin, $config);
        }
        return $response;
    }

    private function matchOrigin(string $origin, array $config): ?string
    {
        if ($origin === '') return null;

        $allowed = $config['allowed_origins'] ?? [];
        foreach ($allowed as $o) {
            if ($o === $origin) return $origin;
        }

        $patterns = $config['allowed_origins_patterns'] ?? [];
        foreach ($patterns as $pattern) {
            if (@preg_match($pattern, $origin)) {
                if (preg_match($pattern, $origin)) return $origin;
            }
        }
        return null;
    }

    private function applyHeaders($response, string $origin, array $config): void
    {
        $methods = $config['allowed_methods'] ?? ['*'];
        $headers = $config['allowed_headers'] ?? ['*'];
        $exposed = $config['exposed_headers'] ?? [];
        $maxAge = (string) ($config['max_age'] ?? 0);
        $creds = (bool) ($config['supports_credentials'] ?? false);

        $response->headers->set('Access-Control-Allow-Origin', $origin);
        if (!empty($methods)) {
            $response->headers->set('Access-Control-Allow-Methods', is_array($methods) ? implode(',', $methods) : (string) $methods);
        }
        if (!empty($headers)) {
            $response->headers->set('Access-Control-Allow-Headers', is_array($headers) ? implode(',', $headers) : (string) $headers);
        }
        if (!empty($exposed)) {
            $response->headers->set('Access-Control-Expose-Headers', is_array($exposed) ? implode(',', $exposed) : (string) $exposed);
        }
        if ($maxAge !== '0') {
            $response->headers->set('Access-Control-Max-Age', $maxAge);
        }
        if ($creds) {
            $response->headers->set('Access-Control-Allow-Credentials', 'true');
        }
        $response->headers->set('Vary', 'Origin');
    }
}

