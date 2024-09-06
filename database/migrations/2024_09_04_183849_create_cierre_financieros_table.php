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
        Schema::create('cierre_financieros', function (Blueprint $table) {
            $table->id();
            $table->decimal('total_general_ventas', 8, 2)->default(0.00);
            $table->decimal('total_ingreso_bolivares', 8, 2)->default(0.00);
            $table->decimal('total_ingreso_dolares', 8, 2)->default(0.00);
            $table->integer('total_servicios')->default(0);
            $table->integer('total_clientes_atendidos')->default(0);
            $table->decimal('total_membresias_vendidas', 8, 2)->default(0.00);
            $table->decimal('total_gif_card_vendidas', 8, 2)->default(0.00);
            $table->decimal('total_productos_vendidos', 8, 2)->default(0.00);
            $table->decimal('total_costos_operativos', 8, 2)->default(0.00);
            $table->decimal('total_general_comiciones', 8, 2)->default(0.00);
            $table->decimal('total_comisiones_bolivares', 8, 2)->default(0.00);
            $table->decimal('total_comisiones_dolares', 8, 2)->default(0.00);
            $table->integer('indicador_inventario')->default(0);
            $table->decimal('utilidad_real', 8, 2)->default(0.00);
            $table->decimal('tasa_bcv', 8, 2)->default(0.00);
            $table->string('fecha');
            $table->string('fecha_ini');
            $table->string('fecha_fin');
            $table->string('codigo_quincena');
            $table->integer('numero_quincena');
            $table->string('responsable');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cierre_financieros');
    }
};
