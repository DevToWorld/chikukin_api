<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CmsV2Media extends Model
{
    protected $table = 'cms_v2_media';
    public $incrementing = false;
    protected $keyType = 'string';
    // checksum もコントローラでセットしているため、fillable に追加する
    protected $fillable = ['id','filename','mime','size','data','checksum'];
}
