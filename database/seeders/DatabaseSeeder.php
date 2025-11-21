<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Armando Caleño',
            'email' => 'test@example.com',
            'password' => bcrypt('Admin.1234')
        ]);

        $this->call(RegimeSeeder::class);

        Customer::factory(100)->create();

        $this->call(DeviceTypeSeeder::class);
        $this->call(PaymentMethodSeeder::class);
        // $this->call(CustomerSeeder::class);
        $this->call(EstablishmentSeeder::class);
        $this->call(DeviceSeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(PlanSeeder::class);
    }
}
