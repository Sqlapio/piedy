<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    use HasFactory;

    /**
     * Define table
     */
    protected $table = 'citas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cod_cita',
        'cliente_id',
        'empleado_id',
        'fecha',
        'hora',
        'servicio',
        'status',
    ];
}
