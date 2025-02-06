<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfiguracionNomina extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'iva',
        'isrl',
        'sueldo_gerente_tienda_usd',
        'sueldo_gerente_supervisor_usd',
        'costo_servicio_basico',
        'iva_nomina',
        'comision_servicios_generales',
        'comision_servicios_adicionales',
        'comision_empleado_en_tienda',
        'comision_gerenete_en_tienda',
        'comision_supervisor_en_tienda',
    ];
}