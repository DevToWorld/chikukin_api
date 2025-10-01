<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CmsV2Section extends Model
{
    protected $table = 'cms_v2_sections';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['id','page_id','sort','component_type','props_json','status'];
    protected $casts = [
        'props_json' => 'array',
    ];

    public function page()
    {
        return $this->belongsTo(CmsV2Page::class, 'page_id', 'id');
    }
}

