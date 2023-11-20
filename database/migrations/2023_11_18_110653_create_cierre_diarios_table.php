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
        Schema::create('cierre_diarios', function (Blueprint $table) {
            $table->id();

            $table->decimal('total_pago_usd',8 ,2)->nullable()->default(0.00);
            $table->decimal('total_pago_bsd',8 ,2)->nullable()->default(0.00);

            $table->decimal('total_gastos_pago_usd',8 ,2)->nullable()->default(0.00);
            $table->decimal('total_gastos_pago_bsd',8 ,2)->nullable()->default(0.00);

            $table->decimal('venta_neta_usd',8 ,2)->nullable()->default(0.00);
            $table->decimal('venta_neta_bsd',8 ,2)->nullable()->default(0.00);

            $table->string('fecha');
            $table->string('responsable');
            $table->string('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cierre_diarios');
    }
};
