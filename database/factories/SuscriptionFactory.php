<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Plan;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Suscription>
 */
class SuscriptionFactory extends Factory
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
        $plan = Plan::inRandomOrder()->first()->id;

        return [
            'number' => fake()->unique()->numerify('##########'),
            'start_date' => $start_date,
            'end_date' => Carbon::parse($start_date)->addYear(),
            'status' => fake()->randomElement(['active', 'inactive']),
            'payment_status' => fake()->randomElement(['paid', 'pending', 'partial']),
            'customer_id' => $customer,
            'description' => fake()->words(3, true),
            'plan_id' => $plan
        ];
    }
}
