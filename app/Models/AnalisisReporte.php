<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AnalisisReporte extends Model
{
    use HasFactory;

    protected $table = 'analisis_reportes';

    protected $fillable = [
        'sucursal_id',
        'nomina_general_id',
        'fecha_calculo',
        'fecha_ini',
        'fecha_fin',
        'cod_nomina',
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

        'total_srv',
        'total_comisiones',
        'total_prod',
        'total_compras',
        'total_gastos',
        
        
    ];

    /**
     * Get the user associated with the AnalisisReporte
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function sucursal(): HasOne
    {
        return $this->hasOne(Sucursal::class, 'id', 'sucursal_id');
    }

    /**
     * Get the user associated with the AnalisisReporte
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function nomina(): HasOne
    {
        return $this->hasOne(NominaGeneral::class, 'id', 'nomina_general_id');
    }

}