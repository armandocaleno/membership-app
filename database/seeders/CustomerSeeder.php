<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Establishment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customer::factory()
        ->count(50)
        ->has(Establishment::factory()->count(1)->withDevices()->create())
        ->create();
    }
}
