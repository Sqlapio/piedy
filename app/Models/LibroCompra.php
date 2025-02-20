<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibroCompra extends Model
{
    use HasFactory;

    protected $table = 'libro_compras';

    protected $fillable = [
        'gasto_id',
        'rif',
        'fecha_documento',
        'razon_social',
        'tipo_prov',
        'nro_comprobante',
        'fecha_apli_retencion',
        'nro_planilla_importacion',
        'nro_expediente_importacion',
        'nro_decla_aduana',
        'fecha_decla_aduana',
        'nro_documento',
        'nro_control',
        'nro_nota_debito',
        'nro_nota_credito',
        'tipo_transaccion',
        'nro_doc_afectado',
        'total_importacion_con_iva',
        'impor_exenta_exoneradas',
        'base_imponible_importaciones',
        'iva_importaciones',
        'total_comp_con_iva',
        'comp_sin_derecho_credito',
        'compras_exentas', 
        'compras_exoneradas', 
        'compras_no_sujetas', 
        'base_imponible_internas', 
        'porcen_alicuota_internas', 
        'impuesto_iva_internas', 
        'responsable'
    ];
}