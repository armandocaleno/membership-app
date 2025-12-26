<?php

namespace Database\Factories;

use App\Models\PaymentMethod;
use App\Models\Suscription;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Income>
 */
class IncomeFactory extends Factory
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
        $date = fake()->dateTimeBetween($start, $end);
        $pay_method = PaymentMethod::inRandomOrder()->first()->id;
        $suscription = Suscription::inRandomOrder()->first();
        $id = $suscription->id;
        $model = $suscription::class;
        $total = $suscription->plan->price;
        // $resource = ['id' => $id, 'model' => $model, 'total' => $total];

        return [
            'number' => fake()->unique()->numerify('##########'),
            'date' => $date,
            'total' => $total,
            'description' => fake()->words(3, true),
            'payment_method_id' => $pay_method,
            'incomeable_id' => $id,
            'incomeable_type' => $model
        ];
    }
}
