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
        Schema::create('movimiento_membresias', function (Blueprint $table) {
            $table->id();
            $table->integer('membresia_id');
            $table->string('cod_membresia');
            $table->integer('cliente_id');
            $table->string('fecha_inicio');
            $table->string('fecha_fin');
            $table->decimal('monto',8 ,2)->nullable()->default(0.00);
            $table->string('metodo_pago');
            $table->decimal('pago_usd',8 ,2)->nullable()->default(0.00);
            $table->decimal('pago_bsd',8 ,2)->nullable()->default(0.00);
            $table->string('referencia')->nullable();
            $table->string('status')->default('1'); // 1-activa 2-inactiva 3-cancelada
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimiento_membresias');
    }
};
