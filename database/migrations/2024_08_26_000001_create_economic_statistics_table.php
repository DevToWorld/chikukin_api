<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('economic_statistics', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category')->index();
            $table->text('description')->nullable();
            $table->json('data')->nullable();
            $table->date('period_start')->nullable();
            $table->date('period_end')->nullable();
            $table->string('source')->nullable();
            $table->string('unit')->nullable();
            $table->decimal('value', 20, 4)->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamps();
            
            $table->index(['period_start', 'period_end']);
            $table->index('is_published');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('economic_statistics');
    }
};