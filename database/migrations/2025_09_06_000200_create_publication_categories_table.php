<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('publication_categories')) {
            Schema::create('publication_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->integer('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (Schema::hasTable('publications')) {
            Schema::table('publications', function (Blueprint $table) {
                if (!Schema::hasColumn('publications', 'category_id')) {
                    $table->unsignedBigInteger('category_id')->nullable()->after('category');
                    $table->foreign('category_id')->references('id')->on('publication_categories')->onDelete('set null');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('publications') && Schema::hasColumn('publications', 'category_id')) {
            Schema::table('publications', function (Blueprint $table) {
                $table->dropForeign(['category_id']);
                $table->dropColumn('category_id');
            });
        }
        if (Schema::hasTable('publication_categories')) {
            Schema::dropIfExists('publication_categories');
        }
    }
};
