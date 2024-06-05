<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimientoMembresia extends Model
{
    use HasFactory;

    /**
     * Define table
     */
    protected $table = 'movimiento_membresias';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'membresia_id',
        'cod_membresia',
        'descripcion',
        'cliente_id',
        'fecha_inicio',
        'fecha_fin',
        'monto',
        'metodo_pago',
        'pago_usd',
        'pago_bsd',
        'referencia',
        'responsable',
    ];



}
