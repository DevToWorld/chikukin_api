<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('membership_type', ['free', 'standard', 'premium'])->default('free')->after('email');
            $table->timestamp('membership_expires_at')->nullable()->after('membership_type');
            $table->json('membership_features')->nullable()->after('membership_expires_at');
            $table->boolean('is_active')->default(true)->after('membership_features');
            
            $table->index('membership_type');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'membership_type',
                'membership_expires_at',
                'membership_features',
                'is_active'
            ]);
        });
    }
};