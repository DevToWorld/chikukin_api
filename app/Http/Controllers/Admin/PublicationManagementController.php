<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Publication;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PublicationManagementController extends Controller
{
    /**
     * 刊行物一覧を取得（管理画面用）
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Publication::orderBy('created_at', 'desc');

            // フィルタリング
            if ($request->has('category')) {
                $query->where('category', $request->category);
            }

            if ($request->has('is_published')) {
                $query->where('is_published', $request->boolean('is_published'));
            }

            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }

            $publications = $query->paginate($request->get('per_page', 20));

            return response()->json([
                'success' => true,
                'data' => [
                    'publications' => $publications->items(),
                    'pagination' => [
                        'current_page' => $publications->currentPage(),
                        'total_pages' => $publications->lastPage(),
                        'total_items' => $publications->total()
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '刊行物の取得に失敗しました',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * 刊行物の詳細を取得
     */
    public function show($id): JsonResponse
    {
        try {
            $publication = Publication::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $publication
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '刊行物が見つかりません',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * 刊行物を作成
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'type' => 'required|string|in:pdf,book,digital',
            'publication_date' => 'required|date',
            'author' => 'nullable|string',
            'pages' => 'nullable|integer',
            'membership_level' => 'nullable|string|in:free,standard,premium',
            'is_published' => 'boolean',
            'is_downloadable' => 'boolean',
            'members_only' => 'boolean',
            'cover_image' => 'nullable|file|image|max:5120', // 5MB
            'pdf_file' => 'nullable|file|mimes:pdf|max:20480' // 20MB
        ]);

        DB::beginTransaction();
        try {
            // カバー画像のアップロード
            if ($request->hasFile('cover_image')) {
                $coverPath = $request->file('cover_image')->store('publications/covers', 'public');
                $validated['cover_image'] = Storage::disk('public')->url($coverPath);
            }

            // PDFファイルのアップロード
            if ($request->hasFile('pdf_file')) {
                $pdfPath = $request->file('pdf_file')->store('publications/pdfs', 'public');
                $validated['file_url'] = Storage::disk('public')->url($pdfPath);
                
                // ファイルサイズを記録
                $fileSize = $request->file('pdf_file')->getSize() / 1048576; // MB
                $validated['file_size'] = round($fileSize, 2);
            }

            // デフォルト値設定
            $validated['membership_level'] = $validated['membership_level'] ?? 'free';
            $validated['download_count'] = 0;
            $validated['view_count'] = 0;

            $publication = Publication::create($validated);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => '刊行物を作成しました',
                'data' => $publication
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            
            // アップロードしたファイルを削除
            if (isset($coverPath)) {
                Storage::disk('public')->delete($coverPath);
            }
            if (isset($pdfPath)) {
                Storage::disk('public')->delete($pdfPath);
            }

            return response()->json([
                'success' => false,
                'message' => '刊行物の作成に失敗しました',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * 刊行物を更新
     */
    public function update(Request $request, $id): JsonResponse
    {
        $publication = Publication::findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'category' => 'sometimes|string',
            'type' => 'sometimes|string|in:pdf,book,digital',
            'publication_date' => 'sometimes|date',
            'author' => 'nullable|string',
            'pages' => 'nullable|integer',
            'membership_level' => 'nullable|string|in:free,standard,premium',
            'is_published' => 'sometimes|boolean',
            'is_downloadable' => 'sometimes|boolean',
            'members_only' => 'sometimes|boolean',
            'cover_image' => 'nullable|file|image|max:5120',
            'pdf_file' => 'nullable|file|mimes:pdf|max:20480'
        ]);

        DB::beginTransaction();
        try {
            // カバー画像の更新
            if ($request->hasFile('cover_image')) {
                // 古い画像を削除
                if ($publication->cover_image) {
                    $oldPath = str_replace('/storage/', '', $publication->cover_image);
                    Storage::disk('public')->delete($oldPath);
                }
                
                $coverPath = $request->file('cover_image')->store('publications/covers', 'public');
                $validated['cover_image'] = Storage::disk('public')->url($coverPath);
            }

            // PDFファイルの更新
            if ($request->hasFile('pdf_file')) {
                // 古いPDFを削除
                if ($publication->file_url) {
                    $oldPath = str_replace('/storage/', '', $publication->file_url);
                    Storage::disk('public')->delete($oldPath);
                }
                
                $pdfPath = $request->file('pdf_file')->store('publications/pdfs', 'public');
                $validated['file_url'] = Storage::disk('public')->url($pdfPath);
                
                // ファイルサイズを記録
                $fileSize = $request->file('pdf_file')->getSize() / 1048576; // MB
                $validated['file_size'] = round($fileSize, 2);
            }

            $publication->update($validated);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => '刊行物を更新しました',
                'data' => $publication
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            
            // 新しくアップロードしたファイルを削除
            if (isset($coverPath)) {
                Storage::disk('public')->delete($coverPath);
            }
            if (isset($pdfPath)) {
                Storage::disk('public')->delete($pdfPath);
            }

            return response()->json([
                'success' => false,
                'message' => '刊行物の更新に失敗しました',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * 刊行物を削除
     */
    public function destroy($id): JsonResponse
    {
        try {
            $publication = Publication::findOrFail($id);
            
            // 関連ファイルを削除
            if ($publication->cover_image) {
                $coverPath = str_replace('/storage/', '', $publication->cover_image);
                Storage::disk('public')->delete($coverPath);
            }
            
            if ($publication->file_url) {
                $pdfPath = str_replace('/storage/', '', $publication->file_url);
                Storage::disk('public')->delete($pdfPath);
            }
            
            $publication->delete();

            return response()->json([
                'success' => true,
                'message' => '刊行物を削除しました'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '刊行物の削除に失敗しました',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * PDFファイルをアップロード（単独）
     */
    public function uploadPdf(Request $request, $id): JsonResponse
    {
        $publication = Publication::findOrFail($id);
        
        $request->validate([
            'pdf_file' => 'required|file|mimes:pdf|max:20480' // 20MB
        ]);

        try {
            // 古いPDFを削除
            if ($publication->file_url) {
                $oldPath = str_replace('/storage/', '', $publication->file_url);
                Storage::disk('public')->delete($oldPath);
            }
            
            // 新しいPDFをアップロード
            $pdfPath = $request->file('pdf_file')->store('publications/pdfs', 'public');
            
            // ファイルサイズを計算
            $fileSize = $request->file('pdf_file')->getSize() / 1048576; // MB
            
            $publication->update([
                'file_url' => Storage::disk('public')->url($pdfPath),
                'file_size' => round($fileSize, 2),
                'is_downloadable' => true
            ]);

            return response()->json([
                'success' => true,
                'message' => 'PDFファイルをアップロードしました',
                'data' => [
                    'file_url' => $publication->file_url,
                    'file_size' => $publication->file_size
                ]
            ]);
        } catch (\Exception $e) {
            if (isset($pdfPath)) {
                Storage::disk('public')->delete($pdfPath);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'PDFのアップロードに失敗しました',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * PDFファイルを削除
     */
    public function deletePdf($id): JsonResponse
    {
        try {
            $publication = Publication::findOrFail($id);
            
            if ($publication->file_url) {
                $pdfPath = str_replace('/storage/', '', $publication->file_url);
                Storage::disk('public')->delete($pdfPath);
                
                $publication->update([
                    'file_url' => null,
                    'file_size' => null,
                    'is_downloadable' => false
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'PDFファイルを削除しました'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'PDFの削除に失敗しました',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
