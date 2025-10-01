<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'report_type',
        'fiscal_year',
        'fiscal_period',
        'report_date',
        'revenue',
        'operating_income',
        'net_income',
        'total_assets',
        'total_liabilities',
        'shareholders_equity',
        'additional_data',
        'file_path',
        'is_audited',
        'is_published'
    ];

    protected $casts = [
        'report_date' => 'date',
        'revenue' => 'decimal:2',
        'operating_income' => 'decimal:2',
        'net_income' => 'decimal:2',
        'total_assets' => 'decimal:2',
        'total_liabilities' => 'decimal:2',
        'shareholders_equity' => 'decimal:2',
        'additional_data' => 'array',
        'is_audited' => 'boolean',
        'is_published' => 'boolean'
    ];

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeByCompany($query, $companyName)
    {
        return $query->where('company_name', $companyName);
    }

    public function scopeByYear($query, $year)
    {
        return $query->where('fiscal_year', $year);
    }

    public function scopeAudited($query)
    {
        return $query->where('is_audited', true);
    }
}