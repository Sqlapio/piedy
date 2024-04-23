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
        Schema::create('mov_gift_cards', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('gift_card_id');
            $table->string('cod_gift_card');
            $table->string('cliente_id');
            $table->integer('monto');
            $table->string('fecha');
            $table->string('metodo_pago');
            $table->string('referencia')->nullable();
            $table->string('responsable');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mov_gift_cards');
    }
};
