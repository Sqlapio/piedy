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
        Schema::create('resumen_contables', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('compra_id');
            $table->unsignedInteger('gasto_id');
            $table->unsignedInteger('sucursal_id');
            $table->string('codigo');
            $table->string('tipo');
            $table->decimal('monto_usd', 8, 2)->default(0.00);
            $table->decimal('monto_bsd', 8, 2)->default(0.00);
            $table->decimal('tasa_bcv', 8, 2)->default(0.00);
            $table->string('conversion');
            $table->decimal('total_operacion', 8, 2)->default(0.00);
            $table->string('responsable');
            $table->string('fecha');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resumen_contables');
    }
};