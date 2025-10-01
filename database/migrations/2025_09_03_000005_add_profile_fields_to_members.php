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
                if (!Schema::hasColumn('members', 'position')) {
                    $table->string('position', 100)->nullable()->after('representative_name');
                }
                if (!Schema::hasColumn('members', 'department')) {
                    $table->string('department', 100)->nullable()->after('position');
                }
                if (!Schema::hasColumn('members', 'capital')) {
                    $table->bigInteger('capital')->nullable()->after('company_name');
                }
                if (!Schema::hasColumn('members', 'industry')) {
                    $table->string('industry', 100)->nullable()->after('address');
                    $table->index('industry');
                }
                if (!Schema::hasColumn('members', 'region')) {
                    $table->string('region', 50)->nullable()->after('industry');
                    $table->index('region');
                }
                if (!Schema::hasColumn('members', 'concerns')) {
                    $table->text('concerns')->nullable()->after('region');
                }
                if (!Schema::hasColumn('members', 'notes')) {
                    $table->text('notes')->nullable()->after('concerns');
                }
                if (!Schema::hasColumn('members', 'started_at')) {
                    $table->date('started_at')->nullable()->after('joined_date');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('members')) {
            Schema::table('members', function (Blueprint $table) {
                foreach (['position','department','capital','industry','region','concerns','notes','started_at'] as $col) {
                    if (Schema::hasColumn('members', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
};

