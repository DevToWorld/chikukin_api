<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // 既存があれば更新、無ければ作成（重複エラー回避）
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password123'),
                'is_admin' => true,
                'membership_type' => 'premium',
                'is_active' => true,
            ]
        );
    }
}
