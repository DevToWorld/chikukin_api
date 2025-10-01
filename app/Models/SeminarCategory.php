<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeminarCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'color_code', 'sort_order', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeOrdered($q)
    {
        return $q->orderBy('sort_order')->orderBy('id');
    }

    public function seminars()
    {
        return $this->hasMany(Seminar::class, 'category_id');
    }
}

