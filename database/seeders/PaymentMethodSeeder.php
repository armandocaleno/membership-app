<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaymentMethod::create([
            'name' => 'Efectivo'
        ]);

        PaymentMethod::create([
            'name' => 'Transferencia'
        ]);

        PaymentMethod::create([
            'name' => 'Depósito'
        ]);

        PaymentMethod::create([
            'name' => 'cheque'
        ]);

        PaymentMethod::create([
            'name' => 'Otro'
        ]);
    }
}
