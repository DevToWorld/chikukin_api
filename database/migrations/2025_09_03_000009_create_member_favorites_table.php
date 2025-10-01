<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('member_favorites')) {
            Schema::create('member_favorites', function (Blueprint $table) {
                $table->unsignedBigInteger('member_id');
                $table->unsignedBigInteger('favorite_member_id');
                $table->timestamp('created_at')->useCurrent();

                $table->primary(['member_id', 'favorite_member_id'], 'pk_member_favorites');
                $table->index('favorite_member_id');

                $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
                $table->foreign('favorite_member_id')->references('id')->on('members')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('member_favorites')) {
            Schema::dropIfExists('member_favorites');
        }
    }
};

