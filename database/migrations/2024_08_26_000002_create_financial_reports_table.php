<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('financial_reports', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('report_type');
            $table->string('fiscal_year');
            $table->string('fiscal_period');
            $table->date('report_date');
            $table->decimal('revenue', 20, 2)->nullable();
            $table->decimal('operating_income', 20, 2)->nullable();
            $table->decimal('net_income', 20, 2)->nullable();
            $table->decimal('total_assets', 20, 2)->nullable();
            $table->decimal('total_liabilities', 20, 2)->nullable();
            $table->decimal('shareholders_equity', 20, 2)->nullable();
            $table->json('additional_data')->nullable();
            $table->string('file_path')->nullable();
            $table->boolean('is_audited')->default(false);
            $table->boolean('is_published')->default(false);
            $table->timestamps();
            
            $table->index(['company_name', 'fiscal_year']);
            $table->index('report_type');
            $table->index('report_date');
            $table->index('is_published');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('financial_reports');
    }
};