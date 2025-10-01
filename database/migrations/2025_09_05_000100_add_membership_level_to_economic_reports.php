<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('economic_reports') && !Schema::hasColumn('economic_reports', 'membership_level')) {
            Schema::table('economic_reports', function (Blueprint $table) {
                $table->string('membership_level', 20)->default('free')->after('members_only');
            });

            // 既存のmembers_onlyをmembership_levelに反映
            DB::table('economic_reports')->where('members_only', true)->update(['membership_level' => 'standard']);
            DB::table('economic_reports')->where('members_only', false)->update(['membership_level' => 'free']);
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('economic_reports') && Schema::hasColumn('economic_reports', 'membership_level')) {
            Schema::table('economic_reports', function (Blueprint $table) {
                $table->dropColumn('membership_level');
            });
        }
    }
};

