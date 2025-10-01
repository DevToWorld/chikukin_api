<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('publications')) {
            Schema::table('publications', function (Blueprint $table) {
                if (!Schema::hasColumn('publications', 'file_size')) {
                    $table->decimal('file_size', 8, 2)->nullable()->after('file_url'); // MB 単位
                }
                if (!Schema::hasColumn('publications', 'membership_level')) {
                    $table->string('membership_level', 20)->default('free')->after('members_only');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('publications')) {
            Schema::table('publications', function (Blueprint $table) {
                if (Schema::hasColumn('publications', 'file_size')) {
                    $table->dropColumn('file_size');
                }
                if (Schema::hasColumn('publications', 'membership_level')) {
                    $table->dropColumn('membership_level');
                }
            });
        }
    }
};

