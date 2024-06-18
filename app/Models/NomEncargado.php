<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NomEncargado extends Model
{
    /**
     * Define table
     */
    protected $table = 'nom_encargados';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'total_servicios',
        'total_comision_dolares',
        'asignaciones_bolivares',
        'deducciones_dolares',
        'salario_quincenal',
        'fecha_ini',
        'fecha_fin',
        'total_dolares',
        'total_bolivares',
        'quincena',
        'cod_quincena',

    ];
}
