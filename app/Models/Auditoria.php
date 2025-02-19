<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auditoria extends Model
{
    use HasFactory;

    protected $table = 'auditorias';

    protected $fillable = [
        'cod_auditoria',
        'fecha_ini',
        'fecha_fin',
        'sucursal_id',
        'producto_id',
        'contenido_neto',
        'unidad',
        'cantidad_solicitada',
        'gasto_total_usd',
        'consumo_por_servicios',
        'servicios_realizados',
        'existencia_sucursal',
        'existencia_central',
        'responsable',
        'observaciones',
    ];
}