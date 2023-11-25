<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Promocion extends Model
{
    use HasFactory;

    /**
     * Define table
     */
    protected $table = 'promocions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cod_promocion',
        'descripcion',
        'fecha_inicio',
        'fecha_fin',
        'tipo',
        'porcentaje',
        'status',
        'image'
    ];

    /**
     * Get all of the servicios for the Promocion
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function servicios(): HasMany
    {
        return $this->hasMany(Servicio::class, 'promocion_id', 'id');
    }
}
