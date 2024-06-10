<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NomQuiropedista extends Model
{
    /**
     * Define table
     */
    protected $table = 'nom_quiropedistas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'nombre',
        'total_servicios',
        'promedio_duracion_servicios',
        'total_comision_dolares',
        'total_comision_bolivares',
        'asignaciones_dolares',
        'asignaciones_bolivares',
        'deducciones_dolares',
        'deducciones_bolivares',
        'fecha_ini',
        'fecha_fin',
    ];
}
