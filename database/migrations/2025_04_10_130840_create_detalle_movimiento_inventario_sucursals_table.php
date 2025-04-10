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
        Schema::create('detalle_movimiento_inventario_sucursals', function (Blueprint $table) {
            $table->id();
            $table->integer('producto_id');
            $table->integer('consumo');
            $table->integer('cantidad');
            $table->string('fecha_movimiento');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_movimiento_inventario_sucursals');
    }
};