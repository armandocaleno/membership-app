<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $product = Product::first()->id;
        
        Plan::create([
            'name' => 'Básico',
            'devices' => '1',
            'months' => '12',
            'price' => '60.00',
            'product_id' => $product
        ]);

        Plan::create([
            'name' => 'Pro',
            'devices' => '3',
            'months' => '12',
            'price' => '80.00',
            'product_id' => $product
        ]);

        Plan::create([
            'name' => 'Total',
            'devices' => '6',
            'months' => '12',
            'price' => '100.00',
            'product_id' => $product
        ]);
    }
}
