<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customer::create([
            'name' => 'Juan Piguave',
            'ruc' => '0999999999',
            'address' => 'Cdla. Florida Norte Mz. 112 V. 34',
            'phone' => '0933486755',
            'email' => 'juanpiguave@gmail.com',
        ]);
    }
}
