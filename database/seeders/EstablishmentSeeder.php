<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Establishment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EstablishmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customer = Customer::first()->id;
        Establishment::create([
            'code' => '001',
            'name' => 'Oficina',
            'address' => 'Cdla. Alborada X etapa, Mz. 112 V.2',
            'phone' => '0957199463',
            'customer_id' => $customer
        ]);
    }
}
