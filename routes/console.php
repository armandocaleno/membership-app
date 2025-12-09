<?php

use App\Console\Commands\SuscripcionExpire;
use App\Mail\SuscriptionActivated;
use App\Models\Suscription;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;


Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('app:alert')->everyMinute();
