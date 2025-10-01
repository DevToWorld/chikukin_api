<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::updateOrCreate(
            ['email' => 'admin@chikugin-cri.co.jp'],
            [
                'username' => 'admin',
                'password' => Hash::make('admin123'),
                'full_name' => 'システム管理者',
                'role' => 'super_admin',
                'is_active' => true,
            ]
        );

        Admin::updateOrCreate(
            ['email' => 'editor@chikugin-cri.co.jp'],
            [
                'username' => 'editor',
                'password' => Hash::make('editor123'),
                'full_name' => '編集者',
                'role' => 'editor',
                'is_active' => true,
            ]
        );

        Admin::updateOrCreate(
            ['email' => 'viewer@chikugin-cri.co.jp'],
            [
                'username' => 'viewer',
                'password' => Hash::make('viewer123'),
                'full_name' => '閲覧者',
                'role' => 'viewer',
                'is_active' => true,
            ]
        );
    }
}
