<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class EconomicReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'category',
        'year',
        'publication_date',
        'author',
        'publisher',
        'keywords',
        'cover_image',
        'file_url',
        'file_size',
        'file_size_bytes',
        'pages',
        'is_downloadable',
        'members_only',
        'membership_level',
        'is_featured',
        'is_published',
        'download_count',
        'sort_order'
    ];

    protected $casts = [
        'publication_date' => 'date',
        'is_downloadable' => 'boolean',
        'members_only' => 'boolean',
        'membership_level' => 'string',
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
    ];

    // JSONレスポンスに含めたいアクセサ
    protected $appends = ['cover_image_url'];

    // カテゴリの定数
    const CATEGORIES = [
        'quarterly' => '四半期経済レポート',
        'annual' => '年次経済統計',
        'regional' => '地域経済調査',
        'industry' => '産業別統計'
    ];

    /**
     * 公開されているレポートのスコープ
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * 特集レポートのスコープ
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * カテゴリでフィルタリングするスコープ
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * 年でフィルタリングするスコープ
     */
    public function scopeByYear($query, $year)
    {
        return $query->where('year', $year);
    }

    /**
     * カテゴリ名を取得
     */
    public function getCategoryNameAttribute()
    {
        return self::CATEGORIES[$this->category] ?? $this->category;
    }

    /**
     * ファイルサイズを人間が読みやすい形式で取得
     */
    public function getFormattedFileSizeAttribute()
    {
        // 優先: バイトカラム
        if (!is_null($this->file_size_bytes) && $this->file_size_bytes > 0) {
            $bytes = (int) $this->file_size_bytes;
            $units = ['B', 'KB', 'MB', 'GB'];
            $factor = 0;
            while ($bytes >= 1024 && $factor < count($units) - 1) {
                $bytes /= 1024;
                $factor++;
            }
            return sprintf('%.2f %s', $bytes, $units[$factor]);
        }

        // 後方互換: 旧decimal(8,2)のMB値
        if (!is_null($this->file_size) && $this->file_size > 0) {
            return sprintf('%.2f MB', (float) $this->file_size);
        }

        return null;
    }

    /**
     * カバー画像のフルURLを取得
     *
     * 想定される保存形式の例:
     * - フルURL:        https://example.com/path/to/img.jpg
     * - 絶対パス:       /img/image-1.png (フロントのビルド済み静的画像)
     * - storage相対:    economic-reports/covers/xxx.jpg （Storage::disk('public')）
     * - 既にstorage含み: storage/economic-reports/covers/xxx.jpg
     */
    public function getCoverImageUrlAttribute()
    {
        $path = $this->cover_image;

        // 未設定時はフロント側のダミー画像
        if (!$path) {
            return '/img/image-1.png';
        }

        // すでにフルURLの場合はそのまま
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        // 先頭スラッシュから始まる絶対パス（例: /img/...、/storage/...）はそのまま返す
        // フロント側のオリジン相対で解決される
        if (str_starts_with($path, '/')) {
            return $path;
        }

        // 正規化（先頭スラッシュ除去）
        $normalized = ltrim($path, '/');

        // 既に 'storage/' を含む場合は Storage相対に変換
        $storageRelative = $normalized;
        if (str_starts_with($normalized, 'storage/')) {
            $storageRelative = substr($normalized, 8); // 'storage/' を除去
        }

        // 'public/' から始まる場合は symlink公開側のパスに合わせて削る
        if (str_starts_with($storageRelative, 'public/')) {
            $storageRelative = substr($storageRelative, 7); // 'public/' を除去
        }

        // Storage::disk('public') に存在確認（本番でファイルが消えている場合のフォールバックに有効）
        try {
            if (Storage::disk('public')->exists($storageRelative)) {
                return asset('storage/' . $storageRelative);
            }
        } catch (\Throwable $e) {
            // ストレージが未設定等でも安全にフォールバック
        }

        // 最後のフォールバック（デフォルト画像）
        return '/img/image-1.png';
    }

    /**
     * ファイルのフルURLを取得
     */
    public function getFileUrlFullAttribute()
    {
        if (!$this->file_url) {
            return null;
        }

        // すでにフルURLの場合はそのまま返す
        if (str_starts_with($this->file_url, 'http://') || str_starts_with($this->file_url, 'https://')) {
            return $this->file_url;
        }

        // 先頭スラッシュなどを正規化
        $path = ltrim($this->file_url, '/');
        // 既に `storage/` を含む場合は除去（Storage::disk('public')->url で付与されるため）
        if (str_starts_with($path, 'storage/')) {
            $path = substr($path, 8);
        }
        // 既に `public/` を含む場合は除去（publicディスクはpublic/以下が公開ルート）
        if (str_starts_with($path, 'public/')) {
            $path = substr($path, 7);
        }

        // 公開ディスクのURL設定を優先（PUBLIC_STORAGE_URL を尊重）
        try {
            return \Illuminate\Support\Facades\Storage::disk('public')->url($path);
        } catch (\Throwable $e) {
            // フォールバック: APP_URL ベースの asset()
            return asset('storage/' . $path);
        }
    }
}
