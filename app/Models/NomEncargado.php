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
        'nombre',
        'total_comision_dolares',
        'asignaciones_dolares',
        'asignaciones_bolivares',
        'deducciones_dolares',
        'deducciones_bolivares',
        'fecha_ini',
        'fecha_fin',
    ];
}
