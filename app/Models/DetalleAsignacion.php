<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleAsignacion extends Model
{
    use HasFactory;

    /**
     * Define table
     */
    protected $table = 'detalle_asignacions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cod_asignacion',
        'cod_servicio',
        'empleado_id',
        'empleado',
        'servicio_id',
        'servicio',
        'costo',
        'fecha',
        'status',
    ];
}
