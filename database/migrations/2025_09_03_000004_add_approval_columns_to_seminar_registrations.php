<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('seminar_registrations')) {
            Schema::table('seminar_registrations', function (Blueprint $table) {
                if (!Schema::hasColumn('seminar_registrations', 'approval_status')) {
                    $table->string('approval_status', 20)->default('pending')->after('payment_status');
                }
                if (!Schema::hasColumn('seminar_registrations', 'approved_at')) {
                    $table->timestamp('approved_at')->nullable()->after('approval_status');
                }
                if (!Schema::hasColumn('seminar_registrations', 'rejected_at')) {
                    $table->timestamp('rejected_at')->nullable()->after('approved_at');
                }
                if (!Schema::hasColumn('seminar_registrations', 'approved_by')) {
                    $table->foreignId('approved_by')->nullable()->after('rejected_at')->constrained('admins')->nullOnDelete();
                }
                if (!Schema::hasColumn('seminar_registrations', 'rejection_reason')) {
                    $table->text('rejection_reason')->nullable()->after('approved_by');
                }
            });

            Schema::table('seminar_registrations', function (Blueprint $table) {
                $table->index(['seminar_id', 'approval_status']);
                $table->index(['member_id', 'approval_status']);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('seminar_registrations')) {
            Schema::table('seminar_registrations', function (Blueprint $table) {
                if (Schema::hasColumn('seminar_registrations', 'rejection_reason')) {
                    $table->dropColumn('rejection_reason');
                }
                if (Schema::hasColumn('seminar_registrations', 'approved_by')) {
                    $table->dropConstrainedForeignId('approved_by');
                }
                if (Schema::hasColumn('seminar_registrations', 'rejected_at')) {
                    $table->dropColumn('rejected_at');
                }
                if (Schema::hasColumn('seminar_registrations', 'approved_at')) {
                    $table->dropColumn('approved_at');
                }
                if (Schema::hasColumn('seminar_registrations', 'approval_status')) {
                    $table->dropColumn('approval_status');
                }
            });
        }
    }
};

