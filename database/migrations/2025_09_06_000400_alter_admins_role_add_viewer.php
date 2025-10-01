<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // MySQL想定: enumにviewerを追加。環境により失敗する場合は手動で実施。
        try {
            DB::statement("ALTER TABLE admins MODIFY COLUMN role ENUM('super_admin','admin','editor','viewer') NOT NULL DEFAULT 'admin'");
        } catch (\Throwable $e) {
            // Fallback: 無視（SQLite等）
        }
        try {
            DB::statement("ALTER TABLE cms_admins MODIFY COLUMN role ENUM('super_admin','admin','editor','viewer') NOT NULL DEFAULT 'admin'");
        } catch (\Throwable $e) {}
    }

    public function down(): void
    {
        try {
            DB::statement("ALTER TABLE admins MODIFY COLUMN role ENUM('super_admin','admin','editor') NOT NULL DEFAULT 'admin'");
        } catch (\Throwable $e) {}
        try {
            DB::statement("ALTER TABLE cms_admins MODIFY COLUMN role ENUM('super_admin','admin','editor') NOT NULL DEFAULT 'admin'");
        } catch (\Throwable $e) {}
    }
};

