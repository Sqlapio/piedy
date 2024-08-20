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
        Schema::create('venta_productos', function (Blueprint $table) {
            $table->id();
            $table->string('cod_asignacion')->nullable();
            $table->integer('empleado_id')->nullable();
            $table->string('rol')->nullable(); /** TIPOS: Quiropedista, Manicurita, Gerente de Tienda */
            $table->integer('producto_id')->nullable();
            $table->decimal('costo_producto', 5, 2)->nullable()->default(0.00);
            $table->decimal('comision_empleado', 5, 2)->nullable()->default(0.00);
            $table->decimal('comision_gerente', 5, 2)->nullable()->default(0.00);
            $table->string('fecha_venta')->nullable();
            $table->integer('cantidad')->nullable();
            $table->decimal('total_venta', 5, 2)->nullable()->default(0.00);
            $table->integer('status')->default(1); /** 1:facturada, 2:anulada */
            $table->string('responsable')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venta_productos');
    }
};
