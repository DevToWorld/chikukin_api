<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('members')) return;

        // Drop unique constraint on email (PostgreSQL index name convention)
        try { DB::statement('ALTER TABLE members DROP CONSTRAINT IF EXISTS members_email_unique'); } catch (\Throwable $e) {}

        // Change email and representative_name to TEXT to store encrypted payloads safely
        try { DB::statement('ALTER TABLE members ALTER COLUMN email TYPE TEXT'); } catch (\Throwable $e) {}
        try { DB::statement('ALTER TABLE members ALTER COLUMN representative_name TYPE TEXT'); } catch (\Throwable $e) {}

        // Add email_index (lowercased email) for login/search uniqueness
        Schema::table('members', function (Blueprint $table) {
            if (!Schema::hasColumn('members', 'email_index')) {
                $table->string('email_index', 255)->nullable()->after('email');
            }
        });

        // Backfill email_index from existing email values (best-effort)
        try {
            DB::statement("UPDATE members SET email_index = LOWER(TRIM(CAST(email AS TEXT))) WHERE (email_index IS NULL OR email_index = '') AND email IS NOT NULL");
        } catch (\Throwable $e) {}

        // Add unique index on email_index
        try {
            DB::statement('CREATE UNIQUE INDEX IF NOT EXISTS members_email_index_unique ON members (email_index)');
        } catch (\Throwable $e) {}
    }

    public function down(): void
    {
        if (!Schema::hasTable('members')) return;
        // Remove unique index and column (safe rollback)
        try { DB::statement('DROP INDEX IF EXISTS members_email_index_unique'); } catch (\Throwable $e) {}
        Schema::table('members', function (Blueprint $table) {
            if (Schema::hasColumn('members', 'email_index')) {
                $table->dropColumn('email_index');
            }
        });
        // Revert column types (optional; keep TEXT to avoid data loss)
        // try { DB::statement("ALTER TABLE members ALTER COLUMN email TYPE VARCHAR(255)"); } catch (\Throwable $e) {}
        // try { DB::statement("ALTER TABLE members ALTER COLUMN representative_name TYPE VARCHAR(100)"); } catch (\Throwable $e) {}
        // Restore unique on email
        try { DB::statement('ALTER TABLE members ADD CONSTRAINT members_email_unique UNIQUE (email)'); } catch (\Throwable $e) {}
    }
};

