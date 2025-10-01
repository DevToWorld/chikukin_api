<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    private function sanitizePathComponent(string $name): string
    {
        $name = trim($name);
        // Remove control chars
        $name = preg_replace('/[\x00-\x1F\x7F]/u', '', $name);
        // Disallow path separators and reserved characters; replace with a middle dot
        $name = str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '・', $name);
        if ($name === '') { $name = '名称未設定'; }
        // Limit length
        if (function_exists('mb_substr')) {
            $name = mb_substr($name, 0, 100);
        } else {
            $name = substr($name, 0, 100);
        }
        return $name;
    }
    /**
     * メディアファイル一覧を取得
     */
    public function index(Request $request): JsonResponse
    {
        $disk = Storage::disk('public');
        $directory = $request->get('directory', 'media');
        // allow legacy 'public/...' input
        $directory = ltrim(preg_replace('#^public/#', '', $directory), '/');
        $search = $request->get('search', '');
        $type = $request->get('type', ''); // image, document, video, etc.
        
        try {
            $files = $disk->files($directory);
            $mediaFiles = [];

            foreach ($files as $file) {
                $fileName = basename($file);
                
                // 検索フィルター
                if ($search && stripos($fileName, $search) === false) {
                    continue;
                }

                $fileInfo = [
                    'id' => md5($file),
                    'name' => $fileName,
                    'path' => 'public/' . ltrim($file, '/'),
                    'url' => $disk->url($file),
                    'size' => $disk->size($file),
                    'type' => $this->getFileType($fileName),
                    'mime_type' => $disk->mimeType($file),
                    'last_modified' => $disk->lastModified($file),
                    'created_at' => $disk->lastModified($file), // Fallback
                ];

                // タイプフィルター
                if ($type && $fileInfo['type'] !== $type) {
                    continue;
                }

                $mediaFiles[] = $fileInfo;
            }

            // ソート
            $sortBy = $request->get('sort_by', 'last_modified');
            $sortDirection = $request->get('sort_direction', 'desc');
            
            usort($mediaFiles, function ($a, $b) use ($sortBy, $sortDirection) {
                $valueA = $a[$sortBy] ?? 0;
                $valueB = $b[$sortBy] ?? 0;
                
                if ($sortDirection === 'desc') {
                    return $valueB <=> $valueA;
                }
                return $valueA <=> $valueB;
            });

            // ページネーション（簡易版）
            $perPage = $request->get('per_page', 20);
            $page = $request->get('page', 1);
            $offset = ($page - 1) * $perPage;
            $paginatedFiles = array_slice($mediaFiles, $offset, $perPage);

            return response()->json([
                'data' => $paginatedFiles,
                'total' => count($mediaFiles),
                'per_page' => $perPage,
                'current_page' => $page,
                'last_page' => ceil(count($mediaFiles) / $perPage),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'メディアファイルの取得に失敗しました',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * ファイルをアップロード
     */
    public function upload(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:10240', // 10MB
            'directory' => 'nullable|string',
            'name' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'バリデーションエラー',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $file = $request->file('file');
            $disk = Storage::disk('public');
            $directory = $request->get('directory', 'media');
            $directory = ltrim(preg_replace('#^public/#', '', $directory), '/');
            
            // ファイル名を生成（日本語等も保持しつつ安全な文字だけに）
            $fileName = $request->get('name');
            if (!$fileName) {
                $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            } else {
                $base = $this->sanitizePathComponent(pathinfo($fileName, PATHINFO_FILENAME));
                $fileName = $base . '.' . $file->getClientOriginalExtension();
            }

            // ファイルを保存
            $path = $file->storeAs($directory, $fileName, 'public');

            $fileInfo = [
                'id' => md5($path),
                'name' => $fileName,
                'original_name' => $file->getClientOriginalName(),
                'path' => 'public/' . ltrim($path, '/'),
                'url' => $disk->url($path),
                'size' => $disk->size($path),
                'type' => $this->getFileType($fileName),
                'mime_type' => $file->getMimeType(),
                'uploaded_at' => now(),
            ];

            return response()->json([
                'message' => 'ファイルをアップロードしました',
                'file' => $fileInfo
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'ファイルのアップロードに失敗しました',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * ファイルを削除
     */
    public function destroy(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'path' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'バリデーションエラー',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $disk = Storage::disk('public');
            $path = $request->path;
            $path = ltrim(preg_replace('#^public/#', '', $path), '/');
            
            if (!$disk->exists($path)) {
                return response()->json([
                    'message' => 'ファイルが見つかりません'
                ], 404);
            }

            $disk->delete($path);

            return response()->json([
                'message' => 'ファイルを削除しました'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'ファイルの削除に失敗しました',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * ファイル情報を更新（名前変更）
     */
    public function update(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'old_path' => 'required|string',
            'new_name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'バリデーションエラー',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $disk = Storage::disk('public');
            $oldPath = ltrim(preg_replace('#^public/#', '', $request->old_path), '/');
            $newName = $request->new_name;
            
            if (!$disk->exists($oldPath)) {
                return response()->json([
                    'message' => 'ファイルが見つかりません'
                ], 404);
            }

            // 新しいパスを生成
            $directory = dirname($oldPath);
            $extension = pathinfo($oldPath, PATHINFO_EXTENSION);
            $newFileName = $this->sanitizePathComponent(pathinfo($newName, PATHINFO_FILENAME)) . '.' . $extension;
            $newPath = $directory . '/' . $newFileName;

            // ファイルを移動（名前変更）
            $disk->move($oldPath, $newPath);

            $fileInfo = [
                'id' => md5($newPath),
                'name' => $newFileName,
                'path' => 'public/' . ltrim($newPath, '/'),
                'url' => $disk->url($newPath),
                'size' => $disk->size($newPath),
                'type' => $this->getFileType($newFileName),
                'mime_type' => $disk->mimeType($newPath),
                'updated_at' => now(),
            ];

            return response()->json([
                'message' => 'ファイル名を変更しました',
                'file' => $fileInfo
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'ファイル名の変更に失敗しました',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * ディレクトリ一覧を取得
     */
    public function directories(): JsonResponse
    {
        try {
            $disk = Storage::disk('public');
            $directories = $disk->directories('');
            $formattedDirectories = array_map(function ($dir) use ($disk) {
                return [
                    'name' => basename($dir),
                    'path' => 'public/' . ltrim($dir, '/'),
                    'file_count' => count($disk->files($dir)),
                ];
            }, $directories);

            return response()->json($formattedDirectories);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'ディレクトリの取得に失敗しました',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * 新しいディレクトリを作成
     */
    public function createDirectory(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'parent' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'バリデーションエラー',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $disk = Storage::disk('public');
            $parent = $request->get('parent', '');
            $parent = ltrim(preg_replace('#^public/#', '', $parent), '/');
            $name = $this->sanitizePathComponent($request->name);
            $directoryPath = ltrim(rtrim($parent, '/'). '/' . $name, '/');

            if ($disk->exists($directoryPath)) {
                return response()->json([
                    'message' => 'ディレクトリは既に存在します'
                ], 409);
            }

            $disk->makeDirectory($directoryPath);

            return response()->json([
                'message' => 'ディレクトリを作成しました',
                'directory' => [
                    'name' => $name,
                    'path' => 'public/' . $directoryPath,
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'ディレクトリの作成に失敗しました',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * メディア統計を取得
     */
    public function stats(): JsonResponse
    {
        try {
            $disk = Storage::disk('public');
            $files = $disk->allFiles('media');
            $totalSize = 0;
            $typeCount = [
                'image' => 0,
                'document' => 0,
                'video' => 0,
                'audio' => 0,
                'other' => 0,
            ];

            foreach ($files as $file) {
                $totalSize += $disk->size($file);
                $type = $this->getFileType(basename($file));
                $typeCount[$type] = ($typeCount[$type] ?? 0) + 1;
            }

            return response()->json([
                'total_files' => count($files),
                'total_size' => $totalSize,
                'total_size_formatted' => $this->formatBytes($totalSize),
                'file_types' => $typeCount,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => '統計の取得に失敗しました',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * ファイルタイプを判定
     */
    private function getFileType(string $fileName): string
    {
        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        $imageTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp'];
        $documentTypes = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt'];
        $videoTypes = ['mp4', 'avi', 'mov', 'wmv', 'flv', 'webm'];
        $audioTypes = ['mp3', 'wav', 'ogg', 'aac', 'flac'];

        if (in_array($extension, $imageTypes)) return 'image';
        if (in_array($extension, $documentTypes)) return 'document';
        if (in_array($extension, $videoTypes)) return 'video';
        if (in_array($extension, $audioTypes)) return 'audio';
        
        return 'other';
    }

    /**
     * バイト数をフォーマット
     */
    private function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes >= 1024 && $i < 4; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
