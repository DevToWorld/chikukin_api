<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('economic_indicators', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // 指標名
            $table->string('category'); // カテゴリー（景気、個人消費、投資、等）
            $table->text('description')->nullable(); // 説明
            $table->string('source')->nullable(); // データソース
            $table->string('frequency')->default('monthly'); // 頻度（monthly, quarterly, annual）
            $table->string('unit')->nullable(); // 単位
            $table->string('link_url')->nullable(); // リンク先URL
            $table->date('publication_date')->nullable(); // 公表日
            $table->json('metadata')->nullable(); // 追加のメタデータ
            $table->boolean('is_active')->default(true); // アクティブ状態
            $table->integer('sort_order')->default(0); // ソート順
            $table->timestamps();
            
            $table->index(['category', 'is_active']);
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('economic_indicators');
    }
};
