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
        // member_activity_logs テーブル作成
        if (!Schema::hasTable('member_activity_logs')) {
        Schema::create('member_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->nullable()->constrained('members')->onDelete('cascade');
            $table->string('action_type', 50); // 'login', 'download', 'view_restricted'
            $table->string('target_type', 50)->nullable();
            $table->bigInteger('target_id')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            // インデックス
            $table->index(['member_id', 'action_type']);
            $table->index('created_at');
        });
        }
        
        // content_access_logs テーブル作成
        if (!Schema::hasTable('content_access_logs')) {
        Schema::create('content_access_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->nullable()->constrained('members')->onDelete('set null');
            $table->string('content_type', 50); // 'publication', 'seminar', 'news'
            $table->bigInteger('content_id');
            $table->string('access_type', 20); // 'view', 'download', 'denied'
            $table->string('required_level', 20)->nullable();
            $table->string('user_level', 20)->nullable();
            $table->timestamps();
            
            // インデックス
            $table->index(['member_id', 'content_type']);
            $table->index(['content_type', 'content_id']);
            $table->index('created_at');
        });
        }
        
        // membership_upgrades テーブル作成
        if (!Schema::hasTable('membership_upgrades')) {
        Schema::create('membership_upgrades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members')->onDelete('cascade');
            $table->string('from_type', 20)->nullable();
            $table->string('to_type', 20);
            $table->text('reason')->nullable();
            $table->decimal('amount', 10, 2)->nullable(); // アップグレード料金
            $table->string('payment_method', 50)->nullable();
            $table->timestamps();
            
            // インデックス
            $table->index('member_id');
            $table->index(['from_type', 'to_type']);
            $table->index('created_at');
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('membership_upgrades');
        Schema::dropIfExists('content_access_logs');
        Schema::dropIfExists('member_activity_logs');
    }
};
