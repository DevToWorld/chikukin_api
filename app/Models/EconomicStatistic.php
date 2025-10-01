<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EconomicStatistic extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'category',
        'description',
        'data',
        'period_start',
        'period_end',
        'source',
        'unit',
        'value',
        'is_published'
    ];

    protected $casts = [
        'data' => 'array',
        'period_start' => 'date',
        'period_end' => 'date',
        'value' => 'decimal:4',
        'is_published' => 'boolean'
    ];

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeInPeriod($query, $start, $end)
    {
        return $query->whereBetween('period_start', [$start, $end])
                     ->orWhereBetween('period_end', [$start, $end]);
    }
}