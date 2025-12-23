<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Customer extends Model
{
    /** @use HasFactory<\Database\Factories\CustomerFactory> */
    use HasFactory;

    protected $guarded =['id', 'created_at', 'updated_at'];

    public function regime() : BelongsTo {
        return $this->belongsTo(Regime::class);
    }

    public function establishments() : HasMany {
        return $this->hasMany(Establishment::class);
    }

    public function supports() : HasMany {
        return $this->hasMany(Support::class);
    }

    public function suscriptions() : HasMany {
        return $this->hasMany(Suscription::class);
    }

     /**
     * Get the devices's customer.
     */
    public function devices(): HasManyThrough
    {
        return $this->HasManyThrough(Device::class, Establishment::class);
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

    /**
     * devuelve el query de clientes activos por rango de fechas
     */
    public function scopeForDateRange(Builder $query, $startDate, $endDate) : Builder {
        return $query->where('status', 'active')->whereBetween('created_at', [$startDate, $endDate]);
    }

    public static function getProvinces() : array {
        return [
            "Azuay" => "Azuay",
            "Bolívar" => "Bolívar",
            "Cañar" => "Cañar",
            "Carchi" => "Carchi",
            "Chimborazo" => "Chimborazo",
            "Cotopaxi" => "Cotopaxi",
            "El Oro" => "El Oro",
            "Esmeraldas" => "Esmeraldas",
            "Galápagos" => "Galápagos",
            "Guayas" => "Guayas",
            "Imbabura" => "Imbabura",
            "Loja" => "Loja",
            "Los Ríos" => "Los Ríos",
            "Manabí" => "Manabí",
            "Morona Santiago" => "Morona Santiago",
            "Napo" => "Napo",
            "Orellana" => "Orellana",
            "Pastaza" => "Pastaza",
            "Pichincha" => "Pichincha",
            "Santa Elena" => "Santa Elena",
            "Santo Domingo de los Tsáchilas" => "Santo Domingo de los Tsáchilas",
            "Sucumbíos" => "Sucumbíos",
            "Tungurahua" => "Tungurahua",
            "Zamora Chinchipe" => "Zamora Chinchipe"
        ];
    }
}
