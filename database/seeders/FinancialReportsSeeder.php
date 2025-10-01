<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FinancialReportsSeeder extends Seeder
{
    public function run(): void
    {
        // 対応テーブルが無い環境もあるため、存在チェックのみ行い、存在すれば軽くサンプル投入
        if (!Schema::hasTable('financial_reports')) {
            return;
        }

        $now = Carbon::now();
        $exists = DB::table('financial_reports')->exists();
        if ($exists) return;

        DB::table('financial_reports')->insert([
            'company_name' => 'サンプル株式会社',
            'report_type' => 'quarterly',
            'fiscal_year' => (string) $now->year,
            'fiscal_period' => 'Q1',
            'report_date' => $now->toDateString(),
            'revenue' => 123456789,
            'operating_income' => 9876543,
            'net_income' => 9876543,
            'is_published' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}
