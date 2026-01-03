<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'products' => 'array'
    ];

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

    /**
     * Get all of the suscriptions for the Plan
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function suscriptions(): HasMany
    {
        return $this->hasMany(Suscription::class);
    }
}
