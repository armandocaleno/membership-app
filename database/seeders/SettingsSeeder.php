<?php

namespace Database\Seeders;

use App\Models\Settings;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Settings::create([
            'name' => 'days_advance',
            'value' => '30',
            'type' => 'integer',
            'description' => 'Días de anticipación de las notificaciones por vencimiento de suscripciones.',
        ]);

        Settings::create([
            'name' => 'send_email_notification',
            'value' => '0',
            'type' => 'boolean',
            'description' => 'Enviar una notificación al cliente cuando la suscripción sea creada.',
        ]);
    }
}
