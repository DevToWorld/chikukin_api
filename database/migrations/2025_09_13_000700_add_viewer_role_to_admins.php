<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // PostgreSQL: relax/replace enum-like CHECK constraint to include 'viewer'
        try {
            DB::statement('ALTER TABLE admins DROP CONSTRAINT IF EXISTS admins_role_check');
        } catch (\Throwable $e) {}
        try {
            DB::statement("ALTER TABLE admins ADD CONSTRAINT admins_role_check CHECK (role IN ('super_admin','admin','editor','viewer'))");
        } catch (\Throwable $e) {}
    }

    public function down(): void
    {
        // Revert to original set (super_admin, admin, editor)
        try { DB::statement('ALTER TABLE admins DROP CONSTRAINT IF EXISTS admins_role_check'); } catch (\Throwable $e) {}
        try { DB::statement("ALTER TABLE admins ADD CONSTRAINT admins_role_check CHECK (role IN ('super_admin','admin','editor'))"); } catch (\Throwable $e) {}
    }
};

