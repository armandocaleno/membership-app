<?php

namespace App\Observers;

use App\Models\Income;
use App\Models\Suscription;
use Filament\Notifications\Notification;

class IncomeObserver
{
    /**
     * Handle the Income "created" event.
     */
    public function created(Income $income): void
    {
        $resource = $income->incomeable;
        $total = 0;
        $plan_price = 0;

        if ($resource instanceof Suscription) {
            $total = $resource->incomes()->sum('total');
            $plan_price = $resource->plan->price;

            if ($total == $plan_price) {
                $resource->payment_status = 'paid';
            }else {
                $resource->payment_status = 'partial';
            }

            $resource->update();
             Notification::make()
             ->title('Actualizado! '. $resource->payment_status)
            ->send();
        }else {
            Notification::make()
            ->title('No Actualizado!')
            ->send();
        }
    }

    /**
     * Handle the Income "updated" event.
     */
    public function updated(Income $income): void
    {
        //
    }

    /**
     * Handle the Income "deleted" event.
     */
    public function deleted(Income $income): void
    {
        //
    }

    /**
     * Handle the Income "restored" event.
     */
    public function restored(Income $income): void
    {
        //
    }

    /**
     * Handle the Income "force deleted" event.
     */
    public function forceDeleted(Income $income): void
    {
        //
    }
}
