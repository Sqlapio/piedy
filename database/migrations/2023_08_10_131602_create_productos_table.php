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
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('cod_producto')->unique();
            $table->string('categoria_id');
            $table->string('descripcion');
            $table->decimal('precio_venta', 8, 2)->nullable();
            $table->integer('existencia');
            $table->string('fecha_carga');
            $table->string('unidad');
            $table->integer('contenido_neto');
            $table->integer('comision_venta_emp')->nullable();
            $table->integer('comision_venta_gte')->nullable();
            $table->string('image')->nullable();
            $table->string('status')->default('activo');
            $table->string('responsable');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
