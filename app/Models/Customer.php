<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    /** @use HasFactory<\Database\Factories\CustomerFactory> */
    use HasFactory;

    protected $guarded =['id', 'created_at', 'updated_at'];

    public function regime() : BelongsTo {
        return $this->belongsTo(Regime::class);
    }

    public function establilshments() : HasMany {
        return $this->hasMany(Establishment::class);
    }

    public function supports() : HasMany {
        return $this->hasMany(Support::class);
    }

    public function suscriptions() : HasMany {
        return $this->hasMany(Suscription::class);
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
