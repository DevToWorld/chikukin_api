<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // membersテーブル: 'basic'を'free'に変換
        if (Schema::hasTable('members') && Schema::hasColumn('members', 'membership_type')) {
            // ENUMカラムを再定義（PostgreSQLの場合はCHECK制約を使用）
            if (config('database.default') === 'pgsql') {
                // まず制約を削除
                DB::statement("ALTER TABLE members DROP CONSTRAINT IF EXISTS members_membership_type_check");
                
                // データを更新
                DB::table('members')
                    ->where('membership_type', 'basic')
                    ->update(['membership_type' => 'free']);
                
                // 新しい制約を追加
                DB::statement("ALTER TABLE members ADD CONSTRAINT members_membership_type_check CHECK (membership_type IN ('free', 'standard', 'premium'))");
            } else {
                // MySQLの場合
                DB::table('members')
                    ->where('membership_type', 'basic')
                    ->update(['membership_type' => 'free']);
                DB::statement("ALTER TABLE members MODIFY COLUMN membership_type ENUM('free', 'standard', 'premium') DEFAULT 'free'");
            }
        }
        
        // publicationsテーブル: 'basic'を'free'に変換
        if (Schema::hasTable('publications') && Schema::hasColumn('publications', 'membership_level')) {
            // カラムの制約を更新
            if (config('database.default') === 'pgsql') {
                // まず制約を削除
                DB::statement("ALTER TABLE publications DROP CONSTRAINT IF EXISTS publications_membership_level_check");
                
                // 既存のデータを更新
                DB::table('publications')
                    ->whereIn('membership_level', ['basic', 'none', null])
                    ->update(['membership_level' => 'free']);
                
                // 新しい制約を追加
                DB::statement("ALTER TABLE publications ADD CONSTRAINT publications_membership_level_check CHECK (membership_level IN ('free', 'standard', 'premium'))");
            } else {
                // 既存のデータを更新
                DB::table('publications')
                    ->whereIn('membership_level', ['basic', 'none', null])
                    ->update(['membership_level' => 'free']);
                DB::statement("ALTER TABLE publications MODIFY COLUMN membership_level ENUM('free', 'standard', 'premium') DEFAULT 'free'");
            }
        }
        
        // seminarsテーブル: 'none'と'basic'を'free'に変換
        if (Schema::hasTable('seminars') && Schema::hasColumn('seminars', 'membership_requirement')) {
            // カラムの制約を更新
            if (config('database.default') === 'pgsql') {
                // まず制約を削除
                DB::statement("ALTER TABLE seminars DROP CONSTRAINT IF EXISTS seminars_membership_requirement_check");
                
                // 既存のデータを更新
                DB::table('seminars')
                    ->whereIn('membership_requirement', ['none', 'basic', null])
                    ->update(['membership_requirement' => 'free']);
                
                // 新しい制約を追加
                DB::statement("ALTER TABLE seminars ADD CONSTRAINT seminars_membership_requirement_check CHECK (membership_requirement IN ('free', 'standard', 'premium'))");
            } else {
                // 既存のデータを更新
                DB::table('seminars')
                    ->whereIn('membership_requirement', ['none', 'basic', null])
                    ->update(['membership_requirement' => 'free']);
                DB::statement("ALTER TABLE seminars MODIFY COLUMN membership_requirement ENUM('free', 'standard', 'premium') DEFAULT 'free'");
            }
        }
        
        // newsテーブル: 同様の処理
        if (Schema::hasTable('news') && Schema::hasColumn('news', 'membership_requirement')) {
            if (config('database.default') === 'pgsql') {
                // まず制約を削除
                DB::statement("ALTER TABLE news DROP CONSTRAINT IF EXISTS news_membership_requirement_check");
                
                // データを更新
                DB::table('news')
                    ->whereIn('membership_requirement', ['none', 'basic', null])
                    ->update(['membership_requirement' => 'free']);
                
                // 新しい制約を追加
                DB::statement("ALTER TABLE news ADD CONSTRAINT news_membership_requirement_check CHECK (membership_requirement IN ('free', 'standard', 'premium'))");
            } else {
                DB::table('news')
                    ->whereIn('membership_requirement', ['none', 'basic', null])
                    ->update(['membership_requirement' => 'free']);
                DB::statement("ALTER TABLE news MODIFY COLUMN membership_requirement ENUM('free', 'standard', 'premium') DEFAULT 'free'");
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // membersテーブル: 'free'を'basic'に戻す
        if (Schema::hasTable('members') && Schema::hasColumn('members', 'membership_type')) {
            DB::table('members')
                ->where('membership_type', 'free')
                ->update(['membership_type' => 'basic']);
            
            if (config('database.default') === 'pgsql') {
                DB::statement("ALTER TABLE members DROP CONSTRAINT IF EXISTS members_membership_type_check");
                DB::statement("ALTER TABLE members ADD CONSTRAINT members_membership_type_check CHECK (membership_type IN ('basic', 'standard', 'premium'))");
            } else {
                DB::statement("ALTER TABLE members MODIFY COLUMN membership_type ENUM('basic', 'standard', 'premium') DEFAULT 'basic'");
            }
        }
        
        // publicationsテーブル: 'free'を'basic'に戻す
        if (Schema::hasTable('publications') && Schema::hasColumn('publications', 'membership_level')) {
            DB::table('publications')
                ->where('membership_level', 'free')
                ->update(['membership_level' => 'basic']);
            
            if (config('database.default') === 'pgsql') {
                DB::statement("ALTER TABLE publications DROP CONSTRAINT IF EXISTS publications_membership_level_check");
            } else {
                DB::statement("ALTER TABLE publications MODIFY COLUMN membership_level VARCHAR(255) DEFAULT 'basic'");
            }
        }
        
        // seminarsテーブル: 'free'を'none'に戻す
        if (Schema::hasTable('seminars') && Schema::hasColumn('seminars', 'membership_requirement')) {
            DB::table('seminars')
                ->where('membership_requirement', 'free')
                ->update(['membership_requirement' => 'none']);
            
            if (config('database.default') === 'pgsql') {
                DB::statement("ALTER TABLE seminars DROP CONSTRAINT IF EXISTS seminars_membership_requirement_check");
            } else {
                DB::statement("ALTER TABLE seminars MODIFY COLUMN membership_requirement VARCHAR(255) DEFAULT 'none'");
            }
        }
        
        // newsテーブル: 同様の処理
        if (Schema::hasTable('news') && Schema::hasColumn('news', 'membership_requirement')) {
            DB::table('news')
                ->where('membership_requirement', 'free')
                ->update(['membership_requirement' => 'none']);
            
            if (config('database.default') === 'pgsql') {
                DB::statement("ALTER TABLE news DROP CONSTRAINT IF EXISTS news_membership_requirement_check");
            } else {
                DB::statement("ALTER TABLE news MODIFY COLUMN membership_requirement VARCHAR(255) DEFAULT 'none'");
            }
        }
    }
};