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
        Schema::create('auditorias', function (Blueprint $table) {
            $table->id();
            $table->string('cod_auditoria');
            $table->string('fecha_ini');
            $table->string('fecha_fin');
            $table->integer('sucursal_id');
            $table->integer('producto_id');
            $table->integer('contenido_neto');
            $table->string('unidad');
            $table->integer('cantidad_solicitada');
            $table->decimal('gasto_total_usd',8 ,2)->nullable()->default(0.00);
            $table->integer('consumo_por_servicios');
            $table->integer('servicios_realizados');
            $table->integer('existencia_sucursal');
            $table->integer('existencia_central');
            $table->string('responsable');
            $table->string('observaciones');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auditorias');
    }
};