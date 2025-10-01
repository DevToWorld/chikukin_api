<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            if (!Schema::hasColumn('admins', 'failed_attempts')) {
                $table->unsignedInteger('failed_attempts')->default(0)->after('last_login_at');
            }
            if (!Schema::hasColumn('admins', 'locked_until')) {
                $table->timestamp('locked_until')->nullable()->after('failed_attempts');
            }
            if (!Schema::hasColumn('admins', 'mfa_enabled')) {
                $table->boolean('mfa_enabled')->default(false)->after('locked_until');
            }
            if (!Schema::hasColumn('admins', 'mfa_secret')) {
                $table->string('mfa_secret', 255)->nullable()->after('mfa_enabled');
            }
        });
    }

    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            if (Schema::hasColumn('admins', 'mfa_secret')) {
                $table->dropColumn('mfa_secret');
            }
            if (Schema::hasColumn('admins', 'mfa_enabled')) {
                $table->dropColumn('mfa_enabled');
            }
            if (Schema::hasColumn('admins', 'locked_until')) {
                $table->dropColumn('locked_until');
            }
            if (Schema::hasColumn('admins', 'failed_attempts')) {
                $table->dropColumn('failed_attempts');
            }
        });
    }
};

