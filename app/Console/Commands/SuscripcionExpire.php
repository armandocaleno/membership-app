<?php

namespace App\Console\Commands;

use App\Mail\SuscriptionExpire;
use App\Mail\SuscriptionExpired;
use App\Models\Settings;
use App\Models\Suscription;
use App\Models\User;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SuscripcionExpire extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:alert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days_advance = (int)Settings::where('name', 'days_advance_notifications')->value('value');
        $check_date = Carbon::now()->addDays($days_advance)->format('Y-m-d');
        $expire_suscription_date = Carbon::now()->subDay(1)->format('Y-m-d');
       
        $suscriptions = Suscription::where('status', 'active')->get();
        $user = User::first()->get();

        foreach ($suscriptions as $sus) {
            $expire_date = Carbon::parse($sus->end_date)->format('Y-m-d');
            $customer_mail = $sus->customer->email;

            if ($check_date == $expire_date) {
                Mail::to($customer_mail)->queue(new SuscriptionExpire($sus));

                Notification::make()
                    ->title("Suscripción $sus->number por vencer!")
                    ->info()
                    ->actions([
                        Action::make('Ver')
                            ->button()
                            ->markAsRead()
                            ->url(route('filament.admin.resources.suscriptions.view', ['record' => $sus])),
                    ])
                    ->sendToDatabase($user);
            }

            if ($expire_date == $expire_suscription_date) {
                //customer mail send
                Mail::to($customer_mail)->queue(new SuscriptionExpired($sus));

                //database notification send
                Notification::make()
                    ->title("Suscripción $sus->number expirada!")
                    ->info()
                    ->actions([
                        Action::make('Ver')
                            ->button()
                            ->markAsRead()
                            ->url(route('filament.admin.resources.suscriptions.view', ['record' => $sus])),
                    ])
                    ->sendToDatabase($user);
            }

            if ($expire_date <= $expire_suscription_date) {
                //suscription status change
                $sus->status = 'inactive';
                $sus->save();
            }
        }
    }
}
