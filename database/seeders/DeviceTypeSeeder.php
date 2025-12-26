<?php

namespace Database\Seeders;

use App\Models\DeviceType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DeviceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DeviceType::create([
            'name' => 'PC'
        ]);

        DeviceType::create([
            'name' => 'Laptop'
        ]);

        DeviceType::create([
            'name' => 'Tablet'
        ]);

        DeviceType::create([
           'name' => 'Impresora'
        ]);
    }
}
