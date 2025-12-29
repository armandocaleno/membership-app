<?php

namespace Database\Factories;

use App\Models\DeviceType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Device>
 */
class DeviceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = DeviceType::inRandomOrder()->first()->id;

        return [
            'serial' => fake()->numerify('########'),
            'description' => fake()->words(3, true),
            'device_type_id' => $type,
            // 'remoteDesktopSoftware' => [
            //     'conecction_id' => fake()->numerify('##########'),
            //     'name' => fake()->word()
            // ]
        ];
    }
}
