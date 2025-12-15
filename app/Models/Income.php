<?php

namespace App\Models;

use App\Observers\IncomeObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
 
#[ObservedBy([IncomeObserver::class])]
class Income extends Model
{
    use HasFactory;
    
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
        $resource_total = 0;

        $payments_total = $resource->incomes()->sum('total');

        if ($resource instanceof Suscription) {
            $resource_total = $resource->plan->price;
        }else {
            $resource_total = $resource->total;
        }

        if ($payments_total >= $resource_total) {
                $resource->payment_status = 'paid';
        }elseif($payments_total < $resource_total && $payments_total > 0) {
            $resource->payment_status = 'partial';
        }else {
            $resource->payment_status = 'pending';
        }

        $resource->update();
    }

    public static function getTotalPerPlan($startDate = null, $endDate = null) : array {
        $query = self::query();

        if ($startDate && $endDate) {
            # code...
        }

        $plans = Plan::where('status', 'active')->get();

        // foreach ($plans as $key => $plan) {
        //     $incomeTotal = $this->query()->withSum('suscriptions');
        // }

        return [
            ''
        ];
    }
}
