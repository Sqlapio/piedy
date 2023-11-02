<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asignacion extends Model
{
    use HasFactory;

    /**
     * Define table
     */
    protected $table = 'asignacions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cod_asignacion',
        'cliente_id',
        'cliente',
        'empleado_id',
        'empleado',
        'area_trabajo',
        'cod_servicio',
        'servicio_id',
        'servicio',
        'costo',
        // 'duracion',
        // 'finalizacion',
        'cubiculo_mesa',
        'status',
    ];
}
