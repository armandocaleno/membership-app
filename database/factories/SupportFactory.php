<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Device;
use App\Models\Establishment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Support>
 */
class SupportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = Carbon::now()->startOfYear();  
        $end = Carbon::now()->endOfYear();
        $start_date = fake()->dateTimeBetween($start, $end);
        $customer = Customer::inRandomOrder()->first()->id;
        $establishment = Establishment::where('customer_id', $customer)->inRandomOrder()->first()->id;
        $device = Device::where('establishment_id', $establishment)->inRandomOrder()->first()->id;
        return [
            'date' => $start_date,
            'number' => fake()->unique()->numerify('##########'),
            'detail' => fake()->words(3, true),
            'total' => fake()->randomElement(['15', '20', '25', '30', '50']),
            'customer_id' => $customer,
            'payment_status' => fake()->randomElement(['paid', 'pending', 'partial']),
            'establishment_id' => $establishment,
            'device_id' => $device
        ];
    }
}
