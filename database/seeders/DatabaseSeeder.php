<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Establishment;
use App\Models\Income;
use App\Models\Support;
use App\Models\Suscription;
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
            'email' => 'armandoc8181@gmail.com',
            'password' => bcrypt('admin.1234')
        ]);

        $this->call(RegimeSeeder::class);
        $this->call(DeviceTypeSeeder::class);
        Customer::factory()
        ->count(50)
        ->has(Establishment::factory()->count(1)->withDevices())
        ->create();
        $this->call(PaymentMethodSeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(PlanSeeder::class);

        Suscription::factory(100)->create();
        Support::factory(50)->create();
        Income::factory(75)->create();
    }
}
