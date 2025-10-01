<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoticeCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name','slug','sort_order','is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function scopeOrdered($q)
    {
        return $q->orderBy('sort_order')->orderBy('id');
    }
}

