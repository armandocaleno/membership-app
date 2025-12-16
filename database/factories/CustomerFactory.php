<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Regime;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $regime = Regime::inRandomOrder()->first()->id;
        $provinces = new Collection(Customer::getProvinces());
        return [
            'name' => fake('es_ES')->company(),
            'ruc' => fake()->numerify('#############'),
            'address' => fake()->address(),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->email(),
            'city' => fake('es_Ec')->city(),
            'regime_id' => $regime,
            'province' => $provinces->random()
        ];
    }
}
