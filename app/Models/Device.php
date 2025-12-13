<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Device extends Model
{
    /** @use HasFactory<\Database\Factories\DeviceFactory> */
    use HasFactory;

    protected $casts = [
        'remoteDesktopSoftware' => 'array'
    ];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function deviceType() : BelongsTo {
        return $this->belongsTo(DeviceType::class);
    }

    public function establishment() : BelongsTo {
        return $this->belongsTo(Establishment::class);
    }

     /**
     * Al insertar en la base de datos el atributo lo convierte en minúsculas
     * Al recuperarlo lo presenta con la primera letra en mayúscula. 
     */
    protected function mame(): Attribute
    {
        return new Attribute(
            get: function ($value) {
                return ucwords($value);
            },
            set: function ($value) {
                return strtolower($value);
            }
        );
    }
}
