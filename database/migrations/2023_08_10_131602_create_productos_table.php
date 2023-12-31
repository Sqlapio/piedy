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
            $table->string('proveedor');
            $table->decimal('precio_venta', 8, 2);
            $table->integer('existencia');
            $table->string('fecha_carga');
            $table->integer('comision_id');
            $table->string('image');
            $table->string('status')->default('activo');

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
