<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EconomicIndicator extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'description',
        'source',
        'frequency',
        'unit',
        'link_url',
        'publication_date',
        'metadata',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_active' => 'boolean',
        'publication_date' => 'date'
    ];

    public function category()
    {
        return $this->belongsTo(EconomicIndicatorCategory::class, 'category', 'slug');
    }

    public function data()
    {
        return $this->hasMany(EconomicIndicatorData::class);
    }

    public function latestData()
    {
        return $this->hasOne(EconomicIndicatorData::class)->latest('period_date');
    }

    // スコープ
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }
}
