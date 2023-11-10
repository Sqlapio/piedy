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
        Schema::create('disponibles', function (Blueprint $table) {
            $table->id();
            $table->string('cod_asignacion')->unique();
            $table->integer('cliente_id');
            $table->string('cliente');
            $table->integer('empleado_id');
            $table->string('empleado');
            $table->string('area_trabajo');
            $table->string('cod_servicio');
            $table->integer('servicio_id');
            $table->string('servicio');
            $table->string('servicio_categoria');
            $table->decimal('costo', 8, 2);
            $table->string('status')->default('activo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disponibles');
    }
};
