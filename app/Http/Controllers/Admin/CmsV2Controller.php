<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsV2Media;
use App\Models\CmsV2Page;
use App\Models\CmsV2Section;
use App\Models\CmsV2Override;
use App\Models\CmsV2PageVersion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CmsV2Controller extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('manage-content');
        $q = CmsV2Page::query();
        if ($request->filled('search')) {
            $s = $request->get('search');
            $q->where(function ($x) use ($s) {
                $x->where('slug','like',"%{$s}%")->orWhere('title','like',"%{$s}%");
            });
        }
        // Optionally exclude pages marked as hidden in meta_json
        $excludeHidden = filter_var($request->get('exclude_hidden', false), FILTER_VALIDATE_BOOLEAN);
        if ($excludeHidden) {
            // Works on PostgreSQL/MySQL JSON columns
            $q->where(function ($w) {
                $w->whereNull('meta_json')
                  ->orWhereNull('meta_json->hidden')
                  ->orWhere('meta_json->hidden', '!=', true);
            });
        }
        $pages = $q->orderBy('updated_at','desc')->paginate($request->get('per_page', 20));
        return response()->json(['success' => true, 'data' => $pages]);
    }

    public function show($id)
    {
        Gate::authorize('manage-content');
        $page = CmsV2Page::with('sections')->findOrFail($id);
        return response()->json(['success' => true, 'data' => $page]);
    }

    public function store(Request $request)
    {
        Gate::authorize('manage-content');
        $data = $request->validate([
            'slug' => 'required|string|max:255|unique:cms_v2_pages,slug',
            'title' => 'required|string|max:255',
            'meta_json' => 'array',
        ]);
        // Use UUID to match current DB column types (uuid)
        $id = (string) Str::uuid();
        $page = CmsV2Page::create([
            'id' => $id,
            'slug' => $data['slug'],
            'title' => $data['title'],
            'meta_json' => $data['meta_json'] ?? [],
        ]);
        return response()->json(['success' => true, 'data' => $page], 201);
    }

    public function update(Request $request, $id)
    {
        Gate::authorize('manage-content');
        $page = CmsV2Page::findOrFail($id);
        $data = $request->validate([
            'title' => 'sometimes|string|max:255',
            'meta_json' => 'sometimes|array',
        ]);
        $page->update($data);
        return response()->json(['success' => true, 'data' => $page]);
    }

    public function upsertSection(Request $request, $id, $sid)
    {
        Gate::authorize('manage-content');
        $page = CmsV2Page::findOrFail($id);
        $data = $request->validate([
            'sort' => 'required|integer',
            'component_type' => 'required|string|max:255',
            'props_json' => 'array',
            'status' => 'in:draft,published',
        ]);
        $section = CmsV2Section::find($sid);
        if (!$section) {
            $sidVal = (string) Str::uuid();
            $section = CmsV2Section::create([
                'id' => $sidVal,
                'page_id' => $page->id,
                'sort' => $data['sort'],
                'component_type' => $data['component_type'],
                'props_json' => $data['props_json'] ?? [],
                'status' => $data['status'] ?? 'draft',
            ]);
        } else {
            $section->update([
                'sort' => $data['sort'],
                'component_type' => $data['component_type'],
                'props_json' => $data['props_json'] ?? [],
                'status' => $data['status'] ?? $section->status,
            ]);
        }
        return response()->json(['success' => true, 'data' => $section]);
    }

    public function publish($id)
    {
        Gate::authorize('manage-content');
        $page = CmsV2Page::with('sections')->findOrFail($id);
        $snapshot = [
            'slug' => $page->slug,
            'title' => $page->title,
            'meta' => $page->meta_json ?? [],
            'sections' => $page->sections->map(function ($s) {
                return [
                    'id' => $s->id,
                    'sort' => $s->sort,
                    'component_type' => $s->component_type,
                    'props' => $s->props_json ?? [],
                ];
            })->values()->all(),
        ];
        $page->update(['published_snapshot_json' => $snapshot]);

        // Save version for rollback
        CmsV2PageVersion::create([
            'id' => (string) Str::uuid(),
            'page_id' => $page->id,
            'snapshot_json' => $snapshot,
            'created_by' => optional(request()->user())->id,
        ]);
        return response()->json(['success' => true, 'data' => $page]);
    }

    public function versions($id)
    {
        Gate::authorize('manage-content');
        $page = CmsV2Page::findOrFail($id);
        $versions = CmsV2PageVersion::where('page_id', $page->id)->orderBy('created_at','desc')->paginate(20);
        return response()->json(['success' => true, 'data' => $versions]);
    }

    public function rollback($id, $vid)
    {
        Gate::authorize('manage-content');
        $page = CmsV2Page::findOrFail($id);
        $ver = CmsV2PageVersion::where('page_id', $page->id)->where('id', $vid)->firstOrFail();
        $page->update(['published_snapshot_json' => $ver->snapshot_json]);
        return response()->json(['success' => true, 'data' => $page]);
    }

    public function uploadMedia(Request $request)
    {
        Gate::authorize('manage-content');
        $request->validate(['file' => 'required|file|max:10240']);
        $file = $request->file('file');
        $data = file_get_contents($file->getRealPath());
        $checksum = hash('sha256', $data);
        $media = CmsV2Media::create([
            'id' => (string) Str::uuid(),
            'filename' => $file->getClientOriginalName(),
            'mime' => $file->getClientMimeType(),
            'size' => $file->getSize(),
            'data' => $data,
            'checksum' => $checksum,
        ]);
        return response()->json(['success' => true, 'data' => [
            'id' => $media->id,
            'filename' => $media->filename,
            'mime' => $media->mime,
            'size' => $media->size,
        ]], 201);
    }

    public function setOverride(Request $request)
    {
        Gate::authorize('manage-content');
        $data = $request->validate([
            'slug' => 'required|string',
            'page_id' => 'required|string',
            'enabled' => 'required|boolean',
        ]);
        $o = DB::transaction(function () use ($data, $request) {
            $row = CmsV2Override::updateOrCreate(['slug' => $data['slug']], [
                'page_id' => $data['page_id'],
                'enabled' => $data['enabled'],
                'updated_by' => optional($request->user())->id,
            ]);
            // simple purge hook (app cache; CDN purge can be wired here)
            try { Cache::forget('cms_v2_page_'.$data['slug']); } catch (\Throwable $e) {}
            Log::info('CMS override updated', ['slug' => $data['slug'], 'enabled' => $data['enabled']]);
            return $row;
        });
        return response()->json(['success' => true, 'data' => $o]);
    }

    public function listOverrides()
    {
        Gate::authorize('manage-content');
        $rows = CmsV2Override::orderBy('updated_at','desc')->get();
        return response()->json(['success' => true, 'data' => $rows]);
    }

    // Issue a signed preview token (aud=slug, exp in seconds)
    public function issuePreviewToken($id)
    {
        Gate::authorize('manage-content');
        $page = CmsV2Page::findOrFail($id);
        $slug = $page->slug;
        $exp = now()->addMinutes((int) env('CMS_PREVIEW_TOKEN_TTL_MIN', 30))->timestamp;
        $payload = base64_encode(json_encode(['aud' => $slug, 'exp' => $exp]));
        $sig = hash_hmac('sha256', $payload, config('app.key'));
        $token = $payload.'.'.$sig;
        return response()->json(['success' => true, 'data' => ['token' => $token, 'expires_at' => $exp, 'slug' => $slug]]);
    }
}
