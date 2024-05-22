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
        Schema::create('movimiento_gift_cards', function (Blueprint $table) {
            $table->id();
            $table->string('gift_card_id');
            $table->string('codigo_seguridad');
            $table->integer('cliente_id');
            $table->integer('monto_pagado');
            $table->string('fecha_debito');
            $table->string('responsable');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimiento_gift_cards');
    }
};
