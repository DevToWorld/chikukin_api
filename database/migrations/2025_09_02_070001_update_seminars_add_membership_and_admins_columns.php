<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // seminars テーブルに不足カラムがあれば追加
        if (Schema::hasTable('seminars')) {
            if (!Schema::hasColumn('seminars', 'membership_requirement')) {
                Schema::table('seminars', function (Blueprint $table) {
                    $table->string('membership_requirement', 20)->default('none')->after('status');
                });
            }

            if (!Schema::hasColumn('seminars', 'created_by')) {
                Schema::table('seminars', function (Blueprint $table) {
                    $table->foreignId('created_by')->nullable()->after('notes')->constrained('admins')->nullOnDelete();
                });
            }

            if (!Schema::hasColumn('seminars', 'updated_by')) {
                Schema::table('seminars', function (Blueprint $table) {
                    $table->foreignId('updated_by')->nullable()->after('created_by')->constrained('admins')->nullOnDelete();
                });
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('seminars')) {
            Schema::table('seminars', function (Blueprint $table) {
                if (Schema::hasColumn('seminars', 'updated_by')) {
                    $table->dropConstrainedForeignId('updated_by');
                }
                if (Schema::hasColumn('seminars', 'created_by')) {
                    $table->dropConstrainedForeignId('created_by');
                }
                if (Schema::hasColumn('seminars', 'membership_requirement')) {
                    $table->dropColumn('membership_requirement');
                }
            });
        }
    }
};

