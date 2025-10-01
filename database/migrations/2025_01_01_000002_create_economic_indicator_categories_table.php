<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('economic_indicator_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // カテゴリー名
            $table->string('slug')->unique(); // URLスラッグ
            $table->text('description')->nullable(); // 説明
            $table->string('color_code')->default('#DA5761'); // カテゴリー色
            $table->string('icon')->nullable(); // アイコン
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->index(['is_active', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('economic_indicator_categories');
    }
};
