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
        Schema::create('libro_ventas', function (Blueprint $table) {
            $table->id();
            $table->integer('venta_servicio_id');
            $table->string('fecha_documento');
            $table->string('rif');
            $table->string('razon_social');
            $table->string('nro_planilla_exportacion');
            $table->string('nro_documento');
            $table->string('registro_maquina');
            $table->string('nro_reporte_z');
            $table->string('nro_control');
            $table->string('nro_nota_debito');
            $table->string('nro_nota_credito');
            $table->string('tipo_transaccion');
            $table->string('nro_doc_afectado');
            $table->string('fecha_comp_retencion');
            $table->decimal('total_ventas_con_iva', 8,2)->default(0.00);
            $table->decimal('venta_exentas_contrib')->default(0.00);
            $table->decimal('venta_extraor_contrib')->default(0.00);
            $table->decimal('base_imponible_contrib')->default(0.00);
            $table->decimal('porcen_alicuota_contrib')->default(0.00);
            $table->decimal('impuesto_iva_contrib')->default(0.00);
            $table->decimal('venta_exentas_no_contrib')->default(0.00);
            $table->decimal('venta_extraor_no_contrib')->default(0.00);
            $table->decimal('base_imponible_no_contrib')->default(0.00);
            $table->decimal('porcen_alicuota_no_contrib')->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('libro_ventas');
    }
};