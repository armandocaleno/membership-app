<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Regime extends Model
{
    public $timestamps = false;

    protected $fillable = ['name'];

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
     * Get all of the customers for the Regime
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }
}
