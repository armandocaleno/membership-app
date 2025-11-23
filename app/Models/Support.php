<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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
}
