<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NomManicurista extends Model
{
    use HasFactory;
    /**
     * Define table
     */
    protected $table = 'nom_manicuristas';

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
        'total_propina_bsd',
        'asignaciones_dolares',
        'asignaciones_bolivares',
        'deducciones_dolares',
        'deducciones_bolivares',
        'fecha_ini',
        'fecha_fin',
        'quincena',
        'cod_quincena',
    ];
}
