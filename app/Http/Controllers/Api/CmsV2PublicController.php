<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CmsV2Page;

class CmsV2PublicController extends Controller
{
    public function showBySlug($slug)
    {
        $page = CmsV2Page::where('slug', $slug)->firstOrFail();
        $snapshot = $page->published_snapshot_json;
        return response()->json([
            'success' => true,
            'data' => $snapshot ?? [
                'slug' => $page->slug,
                'title' => $page->title,
                'meta' => $page->meta_json ?? [],
                'sections' => [],
            ]
        ]);
    }

    // Verify signed preview token and return current draft snapshot
    public function preview($slug)
    {
        $token = request('token');
        if (!$token || !str_contains($token, '.')) {
            return response()->json(['success' => false, 'message' => 'invalid token'], 401);
        }
        [$payload, $sig] = explode('.', $token, 2);
        $calc = hash_hmac('sha256', $payload, config('app.key'));
        if (!hash_equals($calc, $sig)) {
            return response()->json(['success' => false, 'message' => 'bad signature'], 401);
        }
        $json = json_decode(base64_decode($payload), true) ?: [];
        if (($json['aud'] ?? '') !== $slug) {
            return response()->json(['success' => false, 'message' => 'bad audience'], 401);
        }
        if (($json['exp'] ?? 0) < time()) {
            return response()->json(['success' => false, 'message' => 'token expired'], 401);
        }

        $page = CmsV2Page::with('sections')->where('slug', $slug)->firstOrFail();
        $snapshot = [
            'slug' => $page->slug,
            'title' => $page->title,
            'meta' => $page->meta_json ?? [],
            'sections' => $page->sections->map(function ($s) { return [
                'id' => $s->id,
                'sort' => $s->sort,
                'component_type' => $s->component_type,
                'props' => $s->props_json ?? [],
            ]; })->values()->all(),
        ];
        return response()->json(['success' => true, 'data' => $snapshot]);
    }
}
