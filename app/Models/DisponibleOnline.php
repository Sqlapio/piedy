<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisponibleOnline extends Model
{
    use HasFactory;

    protected $connection = 'mysql_online';

    /**
     * Define table
     */
    protected $table = 'disponibles';

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
        'status',
    ];
}
