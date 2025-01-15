<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoGananciaPerdida extends Model
{
    use HasFactory;

    protected $fillable = [
        'ingresos_usd',
        'ingresos_bsd',
        'ingresos_totales_usd',
        'costos_directos_usd',
        'margen_bruto',
        'gastos_operativos',
        'gastos_no_operativos',
        'utilidad_operativa_usd',
        'utilidad_antes_impuestos',
        'impuesto',
        'utilidad_neta',
        'notas_explicitas',
        'user_id',
    ];
}
