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
        Schema::create('pre_seleccion_productos', function (Blueprint $table) {
            $table->id();
            $table->string('cod_producto');
            $table->string('cod_pre_seleccion');
            $table->decimal('precio_venta', 8, 2);
            $table->integer('cantidad');
            $table->decimal('total_compra_usd', 8, 2);
            $table->decimal('total_compra_bsd', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pre_seleccion_productos');
    }
};
