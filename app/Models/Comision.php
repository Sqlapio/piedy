<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comision extends Model
{
    use HasFactory;

    /**
     * Define table
     */
    protected $table = 'comisions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cod_comision',
        'empleado_id',
        'ponderacion_id',
        'venta_id',
        'creada_por',
        'porcentaje',
        'fecha_vence',
        'status',
    ];

    // Una Comision pertenece a un Empleado
    public function empleado():HasMany
    {
        return $this->hasMany(Empleado::class, 'empleado_id', 'id');
    }

    // Una Comision está basada en una Ponderación
    public function ponderacion():HasMany
    {
        return $this->hasMany(Ponderacion::class, 'ponderacion_id', 'id');
    }

    public function venta():BelongsTo
    {
        return $this->belongsTo(Venta::class, 'venta_id', 'id');
    }
}
 