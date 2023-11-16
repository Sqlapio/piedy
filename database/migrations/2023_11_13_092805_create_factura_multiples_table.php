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
        Schema::create('factura_multiples', function (Blueprint $table) {
            $table->id();
            $table->string('cod_asignacion')->unique();
            $table->string('responsable');
            $table->string('metodo_pago')->nullable();
            $table->string('referencia')->nullable();
            $table->string('fecha_venta');
            $table->decimal('pago_usd', 8, 2)->default(0.00);
            $table->decimal('pago_bsd', 8, 2)->default(0.00);
            $table->decimal('total_usd', 8, 2)->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('factura_multiples');
    }
};
