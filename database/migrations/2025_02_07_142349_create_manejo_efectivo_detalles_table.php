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
        Schema::create('manejo_efectivo_detalles', function (Blueprint $table) {
            $table->id();
            $table->integer('manejo_efectivo_id');
            $table->decimal('monto', 8, 2);
            $table->decimal('deduccion', 8, 2);
            $table->string('fecha');
            $table->string('observacion')->nullable();
            $table->decimal('total', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manejo_efectivo_detalles');
    }
};