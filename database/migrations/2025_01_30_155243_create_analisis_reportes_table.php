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
        Schema::create('analisis_reportes', function (Blueprint $table) {
            $table->id();
            $table->integer('sucursal_id');
            $table->integer('nomina_general_id');
            $table->string('fecha_calculo');
            $table->string('fecha_ini');
            $table->string('fecha_fin');
            $table->string('cod_nomina');
            $table->decimal('gastos', 8,2)->default(0.00);
            $table->decimal('compras', 8, 2)->default(0.00);
            $table->decimal('productos_asignados', 8, 2)->default(0.00);
            $table->decimal('nomina', 8, 2)->default(0.00);
            $table->decimal('comisiones', 8, 2)->default(0.00);
            $table->decimal('sub_total_egresos', 8, 2)->default(0.00);
            $table->decimal('venta_servicios', 8, 2)->default(0.00);
            $table->decimal('venta_productos', 8, 2)->default(0.00);
            $table->decimal('sub_total_ingresos', 8, 2)->default(0.00);
            $table->decimal('neto', 8, 2)->default(0.00);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analisis_reportes');
    }
};