<?php

namespace App\Models;

use App\Observers\IncomeObserver;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
 
#[ObservedBy([IncomeObserver::class])]
class Income extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function paymentMethod() : BelongsTo {
        return $this->belongsTo(paymentMethod::class);
    }

    /**
     * Get the parent incomeable model (support or suscription).
     */
    public function incomeable(): MorphTo
    {
        return $this->morphTo();
    }

    public function updatePaymentState(Income $income) : void {
        $resource = $income->incomeable;
        $total = 0;
        $plan_price = 0;

        if ($resource instanceof Suscription) {
            $total = $resource->incomes()->sum('total');
            $plan_price = $resource->plan->price;

            if ($total >= $plan_price) {
                $resource->payment_status = 'paid';
            }elseif($total < $plan_price && $total > 0) {
                $resource->payment_status = 'partial';
            }else {
                $resource->payment_status = 'pending';
            }

            $resource->update();
        }else {
            Notification::make()
            ->title('Saldo No Actualizado!')
            ->send();
        }
    }
}
