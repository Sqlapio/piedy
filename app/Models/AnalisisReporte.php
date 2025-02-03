<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnalisisReporte extends Model
{
    use HasFactory;

    protected $table = 'analisis_reporte';

    protected $fillable = [
        'fecha',
        'gastos',
        'compras',
        'productos_asignados',
        'nomina',
        'comisiones',
        'sub_total_egresos',
        'venta_servicios',
        'venta_productos',
        'sub_total_ingresos',
        'neto',
        'cod_nomina',
        'nomina_general_id',
        'fecha_ini',
        'fecha_fin'
        
    ];
}