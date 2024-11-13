<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacturacionMultiple extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cod_asignacion',
        'tipo',
        'empleado_id',
        'servicio_id',
        'sucursal_id',
        'producto_id',
        'cliente_id',
        'costo_usd',
        'costo_bsd',
        'cantidad',
        'serv_asignacion',
    ];
}
