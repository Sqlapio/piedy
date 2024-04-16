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
            $table->string('cod_producto')->nullable();
            $table->integer('producto_id');
            $table->string('user_id');
            $table->string('cantidad');
            $table->string('fecha_entrega');
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
