<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('members')) {
            Schema::table('members', function (Blueprint $table) {
                if (!Schema::hasColumn('members', 'membership_expires_at')) {
                    $table->timestamp('membership_expires_at')->nullable()->after('expiry_date');
                }
                if (!Schema::hasColumn('members', 'is_active')) {
                    $table->boolean('is_active')->default(true)->after('membership_expires_at');
                }
            });

            // Indexes (safe to declare; typically absent beforehand)
            Schema::table('members', function (Blueprint $table) {
                $table->index('is_active');
                $table->index('membership_expires_at');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('members')) {
            Schema::table('members', function (Blueprint $table) {
                if (Schema::hasColumn('members', 'membership_expires_at')) {
                    $table->dropColumn('membership_expires_at');
                }
                if (Schema::hasColumn('members', 'is_active')) {
                    $table->dropColumn('is_active');
                }
            });
        }
    }
};
