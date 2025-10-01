<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EconomicIndicatorCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'color_code',
        'icon',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function indicators()
    {
        return $this->hasMany(EconomicIndicator::class, 'category', 'slug');
    }

    public function activeIndicators()
    {
        return $this->hasMany(EconomicIndicator::class, 'category', 'slug')
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->orderBy('name');
    }

    // スコープ
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    // アクセサ
    public function getIndicatorCountAttribute()
    {
        return $this->indicators()->where('is_active', true)->count();
    }

    // 自動でslugを生成
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }
}
