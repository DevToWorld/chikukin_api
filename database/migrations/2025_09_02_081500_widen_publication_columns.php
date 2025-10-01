<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('publications')) {
            Schema::table('publications', function (Blueprint $table) {
                // 長いURLやBase64を安全に扱うためにtextへ拡張
                if (Schema::hasColumn('publications', 'cover_image')) {
                    $table->text('cover_image')->nullable()->change();
                }
                if (Schema::hasColumn('publications', 'file_url')) {
                    $table->text('file_url')->nullable()->change();
                }
            });
        }
        // CMS版のpublicationsテーブルにも同様の拡張（存在する場合）
        if (Schema::hasTable('publications') && Schema::hasColumn('publications', 'membership_level')) {
            Schema::table('publications', function (Blueprint $table) {
                if (Schema::hasColumn('publications', 'cover_image')) {
                    $table->text('cover_image')->nullable()->change();
                }
                if (Schema::hasColumn('publications', 'file_url')) {
                    $table->text('file_url')->nullable()->change();
                }
            });
        }
    }

    public function down(): void
    {
        // 戻しは省略（255へ戻すとデータ欠損の可能性があるため）
    }
};

