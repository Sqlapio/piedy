<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibroVenta extends Model
{
    use HasFactory;

    protected $table = 'libro_ventas';

    protected $fillable = [
        'venta_servicio_id',
        'fecha_documento',
        'rif',
        'razon_social',
        'nro_planilla_exportacion',
        'nro_documento',
        'registro_maquina',
        'nro_reporte_z',
        'nro_control',
        'nro_nota_debito',
        'nro_nota_credito',
        'tipo_transaccion',
        'nro_doc_afectado',
        'fecha_comp_retencion',
        'total_ventas_con_iva',
        'venta_exentas_contrib',
        'venta_extraor_contrib',
        'base_imponible_contrib',
        'porcen_alicuota_contrib',        
        'impuesto_iva_contrib',        
        'venta_exentas_no_contrib',
        'venta_extraor_no_contrib',
        'base_imponible_no_contrib',
        'porcen_alicuota_no_contrib',
    ];
}