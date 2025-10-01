<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DebugController extends Controller
{
    private function guard(): void
    {
        $enabled = (bool) config('app.enable_debug_endpoints', false);
        if (!app()->environment('local') && !$enabled) {
            abort(404);
        }
    }

    public function storage(Request $request): JsonResponse
    {
        $this->guard();

        $disk = Storage::disk('public');
        $root = (string) config('filesystems.disks.public.root');
        $urlBase = (string) config('filesystems.disks.public.url');
        $inPublic = (bool) env('PUBLIC_DISK_IN_PUBLIC', false);

        $ok = false; $absPath = null; $url = null; $exists = false; $error = null;
        try {
            $dir = 'health';
            $disk->makeDirectory($dir);
            $name = '_debug_' . time() . '.txt';
            $rel = trim($dir . '/' . $name, '/');
            $disk->put($rel, 'ok');
            $ok = true;
            $absPath = $disk->path($rel);
            $url = $disk->url($rel);
            $exists = $disk->exists($rel);
        } catch (\Throwable $e) {
            $error = $e->getMessage();
        }

        $symlinkInfo = null;
        try {
            $link = public_path('storage');
            if (@is_link($link)) {
                $symlinkInfo = ['link' => $link, 'target' => @readlink($link) ?: null];
            } elseif (@is_dir($link)) {
                $symlinkInfo = ['link' => $link, 'target' => null, 'type' => 'directory'];
            }
        } catch (\Throwable $e) { /* ignore */ }

        return response()->json([
            'public_disk' => [
                'root' => $root,
                'real_root' => @realpath($root) ?: $root,
                'url_base' => $urlBase,
                'public_disk_in_public' => $inPublic,
                'writable_root' => @is_writable($root),
                'symlink' => $symlinkInfo,
            ],
            'write_test' => [
                'ok' => $ok,
                'path' => $absPath,
                'url' => $url,
                'exists' => $exists,
                'error' => $error,
            ],
        ]);
    }

    public function rateLimits(Request $request): JsonResponse
    {
        $this->guard();
        $env = fn ($k, $d = null) => env($k, $d);
        return response()->json([
            'api' => [
                'API_RATE_LIMIT_PER_MIN' => (int) $env('API_RATE_LIMIT_PER_MIN', 60),
            ],
            'admin_login' => [
                'ADMIN_LOGIN_MAX_ATTEMPTS' => (int) $env('ADMIN_LOGIN_MAX_ATTEMPTS', 5),
                'ADMIN_LOGIN_DECAY_SECONDS' => (int) $env('ADMIN_LOGIN_DECAY_SECONDS', 60),
                'ADMIN_MAX_FAILED_ATTEMPTS' => (int) $env('ADMIN_MAX_FAILED_ATTEMPTS', 5),
                'ADMIN_LOCK_MINUTES' => (int) $env('ADMIN_LOCK_MINUTES', 15),
            ],
            'admin_rates' => [
                'ADMIN_CMS_RATE_PER_MIN' => (int) $env('ADMIN_CMS_RATE_PER_MIN', 60),
                'ADMIN_PUBLISH_RATE_PER_MIN' => (int) $env('ADMIN_PUBLISH_RATE_PER_MIN', 10),
                'ADMIN_MEDIA_RATE_PER_MIN' => (int) $env('ADMIN_MEDIA_RATE_PER_MIN', 30),
                'ADMIN_OVERRIDES_RATE_PER_MIN' => (int) $env('ADMIN_OVERRIDES_RATE_PER_MIN', 20),
                'ADMIN_PREVIEW_RATE_PER_MIN' => (int) $env('ADMIN_PREVIEW_RATE_PER_MIN', 30),
                'ADMIN_RATE_IP_WHITELIST' => array_filter(array_map('trim', explode(',', (string) $env('ADMIN_RATE_IP_WHITELIST', '')))),
            ],
        ]);
    }
}
