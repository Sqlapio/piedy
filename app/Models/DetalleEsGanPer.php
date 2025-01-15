<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetalleEsGanPer extends Model
{
    use HasFactory;

    protected $table = 'detalle_es_gan_pers';

    protected $fillable = [
        'estatdo_ganancia_perdida_id',
        'ingresos_usd',
        'ingresos_bsd',
        'mano_de_obra',
        'otros_costos_directos',
        'publicidad',
        'comisiones_empleados',
        'sueldos_empleados',
        'alquiler',
        'telefono',
        'internet',
        'gastos_financieros',
        'perdidas_no_recurrentes',
        'ingresos_financieros',
        'ganancias_no_recurrentes',
        'user_id',
        'fecha_ini',
        'fecha_fin',
    ];

    /**
     * Get the user associated with the DetalleEsGanPer
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function estadoGananciaPerdida(): HasOne
    {
        return $this->hasOne(EstadoGananciaPerdida::class, 'id', 'estatdo_ganancia_perdida_id');
    }
}
