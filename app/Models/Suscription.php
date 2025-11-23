<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Suscription extends Model
{
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
}
