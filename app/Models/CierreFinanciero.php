<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CierreFinanciero extends Model
{
    use HasFactory;

    /**
     * Define table
     */
    protected $table = 'cierre_financieros';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'total_general_ventas',
        'total_ingreso_bolivares',
        'total_ingreso_dolares',
        'total_servicios',
        'total_clientes_atendidos',
        'total_membresias_vendidas',
        'total_gif_card_vendidas',
        'total_productos_vendidos',
        'total_costos_operativos',
        'total_general_comiciones',
        'total_comisiones_bolivares',
        'total_comisiones_dolares',
        'indicador_inventario',
        'utilidad_real',
        'tasa_bcv',
        'fecha',
        'fecha_ini',
        'fecha_fin',
        'codigo_quincena',
        'responsable',
    ];
}
