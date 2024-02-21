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
        Schema::create('movimiento_caja_chicas', function (Blueprint $table) {
            $table->id();
            $table->integer('gasto_id');
            $table->integer('caja_chica_id');
            $table->decimal('saldo',8 ,2)->nullable()->default(0.00);
            $table->string('fecha');
            $table->string('responsable');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimiento_caja_chicas');
    }
};
