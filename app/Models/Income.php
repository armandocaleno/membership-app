<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

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
}
