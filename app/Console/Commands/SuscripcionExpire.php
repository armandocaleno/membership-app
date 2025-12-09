<?php

namespace App\Console\Commands;

use App\Mail\SuscriptionExpire;
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
        $check_date = Carbon::now()->addMonth()->format('Y-m-d');
       
        $suscriptions = Suscription::where('status', 'active')->get();
        
        foreach ($suscriptions as $sus) {
            $expire_date = Carbon::parse($sus->end_date)->format('Y-m-d');

            if ($check_date == $expire_date) {
                
                $customer_mail = $sus->customer->email;
                Mail::to($customer_mail)->queue(new SuscriptionExpire($sus));

                $user = User::first()->get();
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
        }
    }
}
