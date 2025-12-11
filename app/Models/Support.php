<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Support extends Model
{
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
    
}
