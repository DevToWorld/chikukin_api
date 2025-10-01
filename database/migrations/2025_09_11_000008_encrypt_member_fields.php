<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

return new class extends Migration
{
    public function up(): void
    {
        // 暗号化に伴い、可変長の暗号文字列を収めるため TEXT 型へ変更
        // ENUMやインデックス対象ではないカラムのみを対象にする
        $driver = config('database.default');

        if ($driver === 'pgsql') {
            $cols = ['phone','postal_code','position','department','address','concerns','notes'];
            foreach ($cols as $c) {
                if (Schema::hasColumn('members', $c)) {
                    DB::statement("ALTER TABLE members ALTER COLUMN {$c} TYPE TEXT");
                }
            }
        } else { // mysql 他
            if (Schema::hasColumn('members', 'phone')) DB::statement("ALTER TABLE members MODIFY COLUMN phone TEXT NULL");
            if (Schema::hasColumn('members', 'postal_code')) DB::statement("ALTER TABLE members MODIFY COLUMN postal_code TEXT NULL");
            if (Schema::hasColumn('members', 'position')) DB::statement("ALTER TABLE members MODIFY COLUMN position TEXT NULL");
            if (Schema::hasColumn('members', 'department')) DB::statement("ALTER TABLE members MODIFY COLUMN department TEXT NULL");
            if (Schema::hasColumn('members', 'address')) DB::statement("ALTER TABLE members MODIFY COLUMN address TEXT NULL");
            if (Schema::hasColumn('members', 'concerns')) DB::statement("ALTER TABLE members MODIFY COLUMN concerns TEXT NULL");
            if (Schema::hasColumn('members', 'notes')) DB::statement("ALTER TABLE members MODIFY COLUMN notes TEXT NULL");
        }

        // 既存データのバックフィル（未暗号化なら暗号化して保存）
        try {
            $columns = array_filter([
                Schema::hasColumn('members', 'phone') ? 'phone' : null,
                Schema::hasColumn('members', 'postal_code') ? 'postal_code' : null,
                Schema::hasColumn('members', 'position') ? 'position' : null,
                Schema::hasColumn('members', 'department') ? 'department' : null,
                Schema::hasColumn('members', 'address') ? 'address' : null,
                Schema::hasColumn('members', 'concerns') ? 'concerns' : null,
                Schema::hasColumn('members', 'notes') ? 'notes' : null,
            ]);

            if (!empty($columns)) {
                $lastId = 0;
                do {
                    $rows = DB::table('members')
                        ->select(array_merge(['id'], $columns))
                        ->where('id', '>', $lastId)
                        ->orderBy('id')
                        ->limit(500)
                        ->get();

                    foreach ($rows as $row) {
                        $updates = [];
                        foreach ($columns as $col) {
                            $val = $row->$col;
                            if ($val === null || $val === '') continue;
                            // 既に暗号化済みかを簡易判定（復号できたらスキップ）
                            $needsEncrypt = true;
                            try {
                                // 復号に失敗すれば未暗号化とみなす
                                Crypt::decryptString($val);
                                $needsEncrypt = false;
                            } catch (\Throwable $e) {
                                $needsEncrypt = true;
                            }
                            if ($needsEncrypt) {
                                $updates[$col] = Crypt::encryptString((string) $val);
                            }
                        }
                        if (!empty($updates)) {
                            DB::table('members')->where('id', $row->id)->update($updates);
                        }
                        $lastId = $row->id;
                    }
                } while ($rows->count() > 0);
            }
        } catch (\Throwable $e) {
            // バックフィルはベストエフォート（失敗してもテーブル変更は有効）
            \Log::warning('Member encryption backfill skipped', ['error' => $e->getMessage()]);
        }
    }

    public function down(): void
    {
        // 型を元の想定に近い形に戻す（厳密な長さは保持しない）
        $driver = config('database.default');
        if ($driver === 'pgsql') {
            if (Schema::hasColumn('members', 'phone')) DB::statement("ALTER TABLE members ALTER COLUMN phone TYPE VARCHAR(255)");
            if (Schema::hasColumn('members', 'postal_code')) DB::statement("ALTER TABLE members ALTER COLUMN postal_code TYPE VARCHAR(20)");
            if (Schema::hasColumn('members', 'position')) DB::statement("ALTER TABLE members ALTER COLUMN position TYPE VARCHAR(100)");
            if (Schema::hasColumn('members', 'department')) DB::statement("ALTER TABLE members ALTER COLUMN department TYPE VARCHAR(100)");
            if (Schema::hasColumn('members', 'address')) DB::statement("ALTER TABLE members ALTER COLUMN address TYPE TEXT");
            if (Schema::hasColumn('members', 'concerns')) DB::statement("ALTER TABLE members ALTER COLUMN concerns TYPE TEXT");
            if (Schema::hasColumn('members', 'notes')) DB::statement("ALTER TABLE members ALTER COLUMN notes TYPE TEXT");
        } else { // mysql
            if (Schema::hasColumn('members', 'phone')) DB::statement("ALTER TABLE members MODIFY COLUMN phone VARCHAR(255) NULL");
            if (Schema::hasColumn('members', 'postal_code')) DB::statement("ALTER TABLE members MODIFY COLUMN postal_code VARCHAR(20) NULL");
            if (Schema::hasColumn('members', 'position')) DB::statement("ALTER TABLE members MODIFY COLUMN position VARCHAR(100) NULL");
            if (Schema::hasColumn('members', 'department')) DB::statement("ALTER TABLE members MODIFY COLUMN department VARCHAR(100) NULL");
            if (Schema::hasColumn('members', 'address')) DB::statement("ALTER TABLE members MODIFY COLUMN address TEXT NULL");
            if (Schema::hasColumn('members', 'concerns')) DB::statement("ALTER TABLE members MODIFY COLUMN concerns TEXT NULL");
            if (Schema::hasColumn('members', 'notes')) DB::statement("ALTER TABLE members MODIFY COLUMN notes TEXT NULL");
        }
    }
};

