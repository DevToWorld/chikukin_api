<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Publication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PublicationsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Publication::query();

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('is_published')) {
            $query->where('is_published', $request->is_published);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%");
            });
        }

        $publications = $query->orderBy('publication_date', 'desc')
                              ->paginate($request->per_page ?? 10);

        return response()->json($publications);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Fallback: handle JSON body even if Content-Type header is missing
        if (empty($request->all())) {
            $raw = json_decode($request->getContent(), true);
            if (is_array($raw)) {
                $request->merge($raw);
            }
        }
        // Normalize optional numeric fields
        if ($request->has('pages') && $request->input('pages') === '') {
            $request->merge(['pages' => null]);
        }
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            // 管理画面のカテゴリーマスタに合わせてスラッグを許容（固定のinから撤廃）
            'category' => 'required|string|max:255',
            'type' => 'required|in:pdf,book,digital',
            'publication_date' => 'required|date',
            'issue_number' => 'nullable|string|max:50',
            'author' => 'nullable|string|max:255',
            'pages' => 'nullable|integer|min:1',
            // 管理側のアップロードは /storage/... を返すため URL 制約を撤廃
            'file_url' => 'nullable|string',
            'cover_image' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'tags' => 'nullable|string',
            'is_published' => 'boolean',
            'is_downloadable' => 'boolean',
            'members_only' => 'boolean',
            // オプション: 会員レベルも許容
            'membership_level' => 'nullable|in:free,standard,premium',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $publication = Publication::create($request->all());

        return response()->json($publication, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $publication = Publication::findOrFail($id);
        
        // 閲覧数をインクリメント
        $publication->increment('view_count');
        
        return response()->json($publication);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Fallback: handle JSON body even if Content-Type header is missing
        if (empty($request->all())) {
            $raw = json_decode($request->getContent(), true);
            if (is_array($raw)) {
                $request->merge($raw);
            }
        }
        // Normalize optional numeric fields
        if ($request->has('pages') && $request->input('pages') === '') {
            $request->merge(['pages' => null]);
        }
        $publication = Publication::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            // 固定一覧から撤廃し、マスタのスラッグを許容
            'category' => 'sometimes|required|string|max:255',
            'type' => 'sometimes|required|in:pdf,book,digital',
            'publication_date' => 'sometimes|required|date',
            'issue_number' => 'nullable|string|max:50',
            'author' => 'nullable|string|max:255',
            'pages' => 'nullable|integer|min:1',
            // /storage/... 形式も通す
            'file_url' => 'nullable|string',
            'cover_image' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'tags' => 'nullable|string',
            'is_published' => 'boolean',
            'is_downloadable' => 'boolean',
            'members_only' => 'boolean',
            'membership_level' => 'nullable|in:free,standard,premium',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $publication->update($request->all());

        return response()->json($publication);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $publication = Publication::findOrFail($id);
        $publication->delete();

        return response()->json(['message' => '刊行物を削除しました'], 200);
    }

    /**
     * Get featured publications
     */
    public function featured()
    {
        $publications = Publication::where('is_published', true)
                                  ->orderBy('view_count', 'desc')
                                  ->limit(5)
                                  ->get();

        return response()->json($publications);
    }

    /**
     * Get latest publications
     */
    public function latest()
    {
        $publications = Publication::where('is_published', true)
                                  ->orderBy('publication_date', 'desc')
                                  ->limit(10)
                                  ->get();

        return response()->json($publications);
    }

    /**
     * Download a publication
     */
    public function download($id)
    {
        $publication = Publication::findOrFail($id);

        if (!$publication->is_downloadable) {
            return response()->json(['error' => 'この刊行物はダウンロードできません'], 403);
        }

        // ダウンロード数をインクリメント
        $publication->increment('download_count');

        return response()->json([
            'download_url' => $publication->file_url,
            'filename' => $publication->title . '.' . strtolower($publication->type)
        ]);
    }
}
