<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CmsV2Override extends Model
{
    protected $table = 'cms_v2_overrides';
    protected $fillable = ['slug','page_id','enabled','updated_by'];
}

