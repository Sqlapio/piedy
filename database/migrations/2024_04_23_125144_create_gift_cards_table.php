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
        Schema::create('gift_cards', function (Blueprint $table) {
            $table->id();
            $table->string('cod_gift_card')->unique();
            $table->string('cliente_id');
            $table->integer('monto');
            $table->integer('saldo');
            $table->string('fecha_emicion');
            $table->string('fecha_vence')->nullable();
            $table->string('responsable');
            $table->string('status')->default(1); // 0: Inactivo, 1: Activo
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gift_cards');
    }
};
