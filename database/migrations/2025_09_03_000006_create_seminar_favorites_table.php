<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('seminar_favorites')) {
            Schema::create('seminar_favorites', function (Blueprint $table) {
                $table->id();
                $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
                $table->foreignId('seminar_id')->constrained('seminars')->cascadeOnDelete();
                $table->timestamp('created_at')->nullable();
                $table->unique(['member_id','seminar_id'], 'uq_seminar_favorites_member_seminar');
                $table->index('member_id');
                $table->index('seminar_id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('seminar_favorites');
    }
};

