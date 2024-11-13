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
            $table->string('cod_asignacion');
            $table->decimal('costo_usd', 8, 2)->defaule(0.00);
            $table->decimal('costo_bsd', 8, 2)->defaule(0.00);
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
