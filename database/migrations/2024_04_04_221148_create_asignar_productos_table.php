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
        Schema::create('asignar_productos', function (Blueprint $table) {
            $table->id();
            $table->string('cod_producto');
            $table->integer('producto_id');
            $table->string('producto');
            $table->string('cantidad');
            $table->string('contenido_neto');
            $table->string('fecha_entrega');
            $table->string('empleado_id');
            $table->string('empleado');
            $table->string('responsable');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asignar_productos');
    }
};
