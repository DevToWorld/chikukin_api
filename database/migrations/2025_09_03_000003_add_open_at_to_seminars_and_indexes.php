<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('seminars')) {
            Schema::table('seminars', function (Blueprint $table) {
                if (!Schema::hasColumn('seminars', 'premium_open_at')) {
                    $table->timestamp('premium_open_at')->nullable()->after('membership_requirement');
                }
                if (!Schema::hasColumn('seminars', 'standard_open_at')) {
                    $table->timestamp('standard_open_at')->nullable()->after('premium_open_at');
                }
                if (!Schema::hasColumn('seminars', 'free_open_at')) {
                    $table->timestamp('free_open_at')->nullable()->after('standard_open_at');
                }
            });

            // Indexes
            Schema::table('seminars', function (Blueprint $table) {
                $table->index('free_open_at');
                $table->index('standard_open_at');
                $table->index('premium_open_at');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('seminars')) {
            Schema::table('seminars', function (Blueprint $table) {
                if (Schema::hasColumn('seminars', 'free_open_at')) {
                    $table->dropIndex(['free_open_at']);
                    $table->dropColumn('free_open_at');
                }
                if (Schema::hasColumn('seminars', 'standard_open_at')) {
                    $table->dropIndex(['standard_open_at']);
                    $table->dropColumn('standard_open_at');
                }
                if (Schema::hasColumn('seminars', 'premium_open_at')) {
                    $table->dropIndex(['premium_open_at']);
                    $table->dropColumn('premium_open_at');
                }
            });
        }
    }
};

