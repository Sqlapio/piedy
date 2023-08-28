<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'creada_por',
        'porcentaje',
        'fecha_vence',
        'status',
    ];
}
 