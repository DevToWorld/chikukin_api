<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('economic_indicator_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('economic_indicator_id')->constrained()->onDelete('cascade');
            $table->date('period_date'); // データの期間（年月日）
            $table->decimal('value', 20, 4)->nullable(); // 数値データ
            $table->string('value_text')->nullable(); // テキストデータ
            $table->string('period_type')->default('monthly'); // monthly, quarterly, annual
            $table->text('notes')->nullable(); // 備考
            $table->string('status')->default('published'); // published, draft, archived
            $table->timestamps();
            
            $table->index(['economic_indicator_id', 'period_date']);
            $table->index(['period_date', 'period_type']);
            $table->unique(['economic_indicator_id', 'period_date', 'period_type'], 'econ_ind_data_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('economic_indicator_data');
    }
};
