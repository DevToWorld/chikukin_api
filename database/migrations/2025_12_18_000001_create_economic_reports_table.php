<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('economic_reports')) {
            Schema::create('economic_reports', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('description')->nullable();
                $table->string('category')->index(); // quarterly, annual, regional, industry
                $table->integer('year');
                $table->date('publication_date');
                $table->string('author')->default('ちくぎん地域経済研究所');
                $table->string('publisher')->default('株式会社ちくぎん地域経済研究所');
                $table->text('keywords')->nullable();
                $table->string('cover_image')->nullable();
                $table->string('file_url')->nullable();
                $table->decimal('file_size', 8, 2)->nullable();
                $table->integer('pages')->default(0);
                $table->boolean('is_downloadable')->default(true);
                $table->boolean('members_only')->default(true);
                $table->boolean('is_featured')->default(false);
                $table->boolean('is_published')->default(false);
                $table->integer('download_count')->default(0);
                $table->integer('sort_order')->default(0);
                $table->timestamps();
                
                $table->index(['category', 'year']);
                $table->index(['is_published', 'year']);
                $table->index('is_featured');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('economic_reports');
    }
};
