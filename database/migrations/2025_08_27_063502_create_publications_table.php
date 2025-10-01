<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('publications', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('category'); // research, quarterly, special, survey
            $table->string('type'); // pdf, book, digital
            $table->date('publication_date');
            $table->string('issue_number')->nullable();
            $table->string('author')->nullable();
            $table->integer('pages')->nullable();
            $table->string('file_url')->nullable();
            $table->string('cover_image')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->text('tags')->nullable();
            $table->boolean('is_published')->default(false);
            $table->boolean('is_downloadable')->default(false);
            $table->boolean('members_only')->default(false);
            $table->integer('download_count')->default(0);
            $table->integer('view_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publications');
    }
};