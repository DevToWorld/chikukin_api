<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Publication extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'category',
        'type',
        'publication_date',
        'issue_number',
        'author',
        'pages',
        'file_url',
        'cover_image',
        'price',
        'tags',
        'is_published',
        'is_downloadable',
        'members_only',
        'membership_level',
        'download_count',
        'view_count',
    ];

    protected $casts = [
        'publication_date' => 'date',
        'is_published' => 'boolean',
        'is_downloadable' => 'boolean',
        'members_only' => 'boolean',
        'membership_level' => 'string',
        'pages' => 'integer',
        'download_count' => 'integer',
        'view_count' => 'integer',
        'price' => 'decimal:2',
    ];

    // フロント表示用の派生プロパティ
    protected $appends = ['cover_image_url'];

    /**
     * カバー画像のフルURLを返す（ストレージ/相対/絶対すべて許容）
     */
    public function getCoverImageUrlAttribute()
    {
        $path = $this->cover_image;

        if (!$path) {
            return '/img/image-1.png';
        }

        // すでに絶対URL
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        // ルート相対（/img/..., /storage/... など）はそのまま
        if (str_starts_with($path, '/')) {
            return $path;
        }

        // 正規化
        $normalized = ltrim($path, '/');

        // 先頭が storage/ の場合は public ディスク相対に直す
        if (str_starts_with($normalized, 'storage/')) {
            $normalized = substr($normalized, 8);
        }
        if (str_starts_with($normalized, 'public/')) {
            $normalized = substr($normalized, 7);
        }

        try {
            if (Storage::disk('public')->exists($normalized)) {
                return asset('storage/' . $normalized);
            }
        } catch (\Throwable $e) {
            // ストレージ未設定でも安全にフォールバック
        }

        return '/img/image-1.png';
    }
}
