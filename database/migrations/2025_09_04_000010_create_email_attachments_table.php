<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('email_attachments')) {
            Schema::create('email_attachments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('campaign_id')->constrained('email_campaigns')->cascadeOnDelete();
                $table->string('disk', 50)->default('public');
                $table->string('path');
                $table->string('filename');
                $table->string('mime')->nullable();
                $table->unsignedBigInteger('size')->default(0);
                $table->timestamps();
                $table->index('campaign_id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('email_attachments');
    }
};

