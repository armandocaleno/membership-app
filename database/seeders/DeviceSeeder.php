<?php

namespace Database\Seeders;

use App\Models\Device;
use App\Models\DeviceType;
use App\Models\Establishment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $type = DeviceType::first()->id;
        $establishment = Establishment::first()->id;
        Device::create([
            'serial' => '37453763',
            'connection_id' => '837462533',
            'device_type_id' => $type,
            'establishment_id' => $establishment
        ]);
    }
}
