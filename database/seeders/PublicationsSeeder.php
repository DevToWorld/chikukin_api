<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class PublicationsSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('publications')) {
            return; // テーブルが無い環境ではスキップ
        }

        $today = Carbon::now();

        $items = [
        ];

        // 既存がある場合は重複を避ける
        foreach ($items as $row) {
            $exists = DB::table('publications')
                ->where('title', $row['title'])
                ->exists();
            if (!$exists) {
                DB::table('publications')->insert($row);
            }
        }
    }
}

