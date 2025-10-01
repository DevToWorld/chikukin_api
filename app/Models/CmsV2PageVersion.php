<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CmsV2PageVersion extends Model
{
    protected $table = 'cms_v2_page_versions';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['id','page_id','snapshot_json','created_by'];
    protected $casts = [ 'snapshot_json' => 'array' ];
}

