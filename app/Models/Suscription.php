<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Suscription extends Model
{
    use Notifiable;
    
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function customer() : BelongsTo {
        return $this->belongsTo(Customer::class);
    }

    public function plan() : BelongsTo {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Get all of the suscription's incomes.
     */
    public function incomes(): MorphMany
    {
        return $this->morphMany(Income::class, 'incomeable');
    }

    public function saldo(): float
    {
        $total_incomes = $this->incomes()->sum('total');
        $total_suscription = $this->plan->price;
        $saldo = $total_suscription - $total_incomes;
        return $saldo;
    }

     /**
     * devuelve el query de suscripciones activas por rango de fechas
     */
    public function scopeForDateRange(Builder $query, $startDate, $endDate) : Builder {
        return $query->where('status', 'active')->whereBetween('created_at', [$startDate, $endDate]);
    }
}
