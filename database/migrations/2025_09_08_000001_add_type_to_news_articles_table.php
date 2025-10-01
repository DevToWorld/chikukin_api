<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('news_articles')) {
            if (!Schema::hasColumn('news_articles', 'type')) {
                Schema::table('news_articles', function (Blueprint $table) {
                    $table->string('type', 50)->default('news')->after('category');
                    $table->index('type');
                });
            }

            // 既存データのうち、category が 'notice' のものは type を 'notice' に更新
            try {
                DB::table('news_articles')->where('category', 'notice')->update(['type' => 'notice']);
            } catch (\Throwable $e) {
                // 失敗してもマイグレーションは継続（環境によってテーブルが空の場合など）
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('news_articles')) {
            Schema::table('news_articles', function (Blueprint $table) {
                // インデックス・カラムが存在する場合のみドロップ
                try { $table->dropIndex(['type']); } catch (\Throwable $e) {}
                if (Schema::hasColumn('news_articles', 'type')) {
                    $table->dropColumn('type');
                }
            });
        }
    }
};
