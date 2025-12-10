<?php

namespace Database\Factories;

use App\Models\Device;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Establishment>
 */
class EstablishmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => fake()->numerify('###'),
            'name' => fake()->name(),
            'address' => fake()->address(),
            'phone' => fake()->phoneNumber()
        ];
    }

    public function withDevices(){
        return $this->has(Device::factory()->count(2));
    }
}
