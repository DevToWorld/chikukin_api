<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CmsV2Page extends Model
{
    protected $table = 'cms_v2_pages';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['id','slug','title','meta_json','published_snapshot_json'];
    protected $casts = [
        'meta_json' => 'array',
        'published_snapshot_json' => 'array',
    ];

    public function sections()
    {
        return $this->hasMany(CmsV2Section::class, 'page_id', 'id')->orderBy('sort', 'asc');
    }
}

