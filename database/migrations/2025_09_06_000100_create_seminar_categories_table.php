<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seminar_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('color_code')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        if (Schema::hasTable('seminars')) {
            Schema::table('seminars', function (Blueprint $table) {
                if (!Schema::hasColumn('seminars', 'category_id')) {
                    $table->unsignedBigInteger('category_id')->nullable()->after('status');
                    $table->foreign('category_id')->references('id')->on('seminar_categories')->onDelete('set null');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('seminars') && Schema::hasColumn('seminars', 'category_id')) {
            Schema::table('seminars', function (Blueprint $table) {
                $table->dropForeign(['category_id']);
                $table->dropColumn('category_id');
            });
        }
        Schema::dropIfExists('seminar_categories');
    }
};
