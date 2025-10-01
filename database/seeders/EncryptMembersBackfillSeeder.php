<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Crypt;

class EncryptMembersBackfillSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('members')) return;

        $columns = array_filter([
            // Newly added sensitive fields
            Schema::hasColumn('members', 'email') ? 'email' : null,
            Schema::hasColumn('members', 'representative_name') ? 'representative_name' : null,
            Schema::hasColumn('members', 'phone') ? 'phone' : null,
            Schema::hasColumn('members', 'postal_code') ? 'postal_code' : null,
            Schema::hasColumn('members', 'position') ? 'position' : null,
            Schema::hasColumn('members', 'department') ? 'department' : null,
            Schema::hasColumn('members', 'address') ? 'address' : null,
            Schema::hasColumn('members', 'concerns') ? 'concerns' : null,
            Schema::hasColumn('members', 'notes') ? 'notes' : null,
        ]);

        if (empty($columns)) return;

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
                    if (is_string($val) && trim($val) === '') {
                        $updates[$col] = null; // 空文字はnullへ正規化（復号エラー回避）
                        continue;
                    }
                    if ($val === null) continue;
                    $needsEncrypt = true;
                    try {
                        // if decrypt succeeds, it's already encrypted
                        Crypt::decryptString($val);
                        $needsEncrypt = false;
                    } catch (\Throwable $e) {
                        $needsEncrypt = true;
                    }
                    if ($needsEncrypt) {
                        $updates[$col] = Crypt::encryptString((string) $val);
                    }
                }
                // Ensure email_index is populated if column exists and email present
                if (Schema::hasColumn('members', 'email_index')) {
                    $rawEmail = $row->email;
                    try {
                        // If encrypted, decrypt to compute index
                        $rawEmail = $rawEmail ? Crypt::decryptString($rawEmail) : $rawEmail;
                    } catch (\Throwable $e) {}
                    if (!empty($rawEmail)) {
                        $updates['email_index'] = mb_strtolower(trim((string)$rawEmail));
                    }
                }
                if (!empty($updates)) {
                    DB::table('members')->where('id', $row->id)->update($updates);
                }
                $lastId = $row->id;
            }
        } while ($rows->count() > 0);
    }
}
