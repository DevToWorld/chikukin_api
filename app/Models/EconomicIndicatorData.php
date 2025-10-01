<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EconomicIndicatorData extends Model
{
    use HasFactory;

    protected $fillable = [
        'economic_indicator_id',
        'period_date',
        'value',
        'value_text',
        'period_type',
        'notes',
        'status'
    ];

    protected $casts = [
        'period_date' => 'date',
        'value' => 'decimal:4'
    ];

    public function indicator()
    {
        return $this->belongsTo(EconomicIndicator::class, 'economic_indicator_id');
    }

    // スコープ
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeByPeriodType($query, $type)
    {
        return $query->where('period_type', $type);
    }

    public function scopeByDateRange($query, $start, $end)
    {
        return $query->whereBetween('period_date', [$start, $end]);
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('period_date', 'desc');
    }

    // アクセサ
    public function getFormattedValueAttribute()
    {
        if ($this->value_text) {
            return $this->value_text;
        }
        
        if ($this->value) {
            $unit = $this->indicator->unit ?? '';
            return number_format($this->value, 2) . ($unit ? ' ' . $unit : '');
        }
        
        return '-';
    }

    public function getFormattedDateAttribute()
    {
        return $this->period_date->format('Y年m月');
    }
}
