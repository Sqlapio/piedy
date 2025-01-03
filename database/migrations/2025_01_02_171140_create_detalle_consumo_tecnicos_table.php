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
        Schema::create('detalle_consumo_tecnicos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('consumo_tecnico_id');
            $table->unsignedBigInteger('producto_id');
            $table->integer('promedio_servicios');
            $table->integer('user_id');
            $table->integer('cantidad');
            $table->string('fecha_entrega');
            $table->string('tipo_movimiento'); //1-primera-entrega  //2-reposicion 
            $table->string('fecha_reposicion');
            $table->integer('total_servicios_realizados');
            $table->integer('responsable_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_consumo_tecnicos');
    }
};