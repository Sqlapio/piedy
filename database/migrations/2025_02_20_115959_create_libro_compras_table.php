<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('libro_compras', function (Blueprint $table) {
            $table->id();
            $table->integer('gasto_id');
            $table->string('fecha_documento');
            $table->string('rif');
            $table->string('razon_social');
            $table->string('tipo_prov');
            $table->string('nro_comprobante');
            $table->string('fecha_apli_retencion');
            $table->string('nro_planilla_importacion');
            $table->string('nro_expediente_importacion');
            $table->string('nro_decla_aduana');
            $table->string('fecha_decla_aduana');
            $table->string('nro_documento');
            $table->string('nro_control');
            $table->string('nro_nota_debito');
            $table->string('nro_nota_credito');
            $table->string('tipo_transaccion');
            $table->string('nro_doc_afectado');
            $table->decimal('total_importacion_con_iva', 8, 2)->default(0.00);
            $table->decimal('impor_exenta_exoneradas', 8, 2)->default(0.00);
            $table->decimal('base_imponible_importaciones, 8, 2')->default(0.00);
            $table->decimal('porcen_alicuota_importaciones', 8, 2)->default(0.00);
            $table->decimal('impuesto_iva_importaciones', 8, 2)->default(0.00);
            $table->decimal('total_comp_con_iva', 8, 2)->default(0.00);
            $table->decimal('comp_sin_derecho_credito', 8, 2)->default(0.00);
            $table->decimal('compras_exentas', 8, 2)->default(0.00);
            $table->decimal('compras_exoneradas', 8, 2)->default(0.00);
            $table->decimal('compras_no_sujetas', 8, 2)->default(0.00);
            $table->decimal('base_imponible_internas', 8, 2)->default(0.00);
            $table->decimal('porcen_alicuota_internas', 8, 2)->default(0.00);
            $table->decimal('impuesto_iva_internas', 8, 2)->default(0.00);
            $table->string('responsable');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('libro_compras');
    }
};