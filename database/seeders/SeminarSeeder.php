<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Seminar;
use App\Models\Admin;
use Carbon\Carbon;

class SeminarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Admin::first();

        $seminars = [
        ];

        foreach ($seminars as $seminarData) {
            Seminar::create($seminarData);
        }
    }
}
