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
        Schema::create('facturacion_multiples', function (Blueprint $table) {
            $table->id();
            $table->json('cod_asignacion');
            $table->string('cod_fac_multiple');
            $table->decimal('venta_total_usd', 8, 2)->defaule(0.00);
            $table->decimal('venta_total_bsd', 8, 2)->defaule(0.00);
            $table->integer('sucursal_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facturacion_multiples');
    }
};
