<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServicesSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}