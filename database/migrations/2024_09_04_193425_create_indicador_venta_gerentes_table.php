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
        Schema::create('indicador_venta_gerentes', function (Blueprint $table) {
            $table->id();
            $table->integer('empleado_id')->default(0);
            $table->integer('gift_card_vendidas')->default(0);
            $table->integer('membresias_vendidas')->default(0);
            $table->integer('servicios_vip_vendidos')->default(0);
            $table->integer('productos_vendidos')->default(0);
            $table->integer('dias_trabajados')->default(0);
            $table->string('fecha_ini');
            $table->string('fecha_fin');
            $table->string('fecha');
            $table->string('codigo_quincena');
            $table->string('numero_quincena');
            $table->string('mes');
            $table->string('responsable');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('indicador_venta_gerentes');
    }
};
