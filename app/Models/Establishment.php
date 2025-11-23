<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Establishment extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function devices() : HasMany {
        return $this->hasMany(Device::class);
    }

    public function customer() : BelongsTo {
        return $this->belongsTo(Customer::class);
    }
}
