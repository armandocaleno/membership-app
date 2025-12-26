<?php

namespace Database\Seeders;

use App\Models\Regime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RegimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Regime::create([
            'name' => 'RIMPE - Negocio Popular'
        ]);

         Regime::create([
            'name' => 'RIMPE - Emprendedor'
        ]);

         Regime::create([
            'name' => 'Régimen general'
        ]);
    }
}
