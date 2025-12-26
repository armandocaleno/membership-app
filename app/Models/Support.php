<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Support extends Model
{
    use HasFactory;
    
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * Get all of the support's incomes.
     */
    public function incomes(): MorphMany
    {
        return $this->morphMany(Income::class, 'incomeable');
    }

    public function customer() : BelongsTo {
        return $this->belongsTo(Customer::class);
    }

    public function establishment() : BelongsTo {
        return $this->belongsTo(Establishment::class);
    }

    public function device() : BelongsTo {
        return $this->belongsTo(Device::class);
    }

    public function saldo(): float
    {
        $total_incomes = $this->incomes()->sum('total');
        $total_support = $this->total;
        $saldo = $total_support - $total_incomes;
        return $saldo;
    }
    
    /**
     * devuelve el query de soportes por rango de fechas
     */
    public function scopeForDateRange(Builder $query, $startDate, $endDate) : Builder {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }
}
