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
            $table->string('codigo_seguridad')->unique();
            $table->string('pgc');
            $table->integer('cliente_id');
            $table->string('cliente');
            $table->integer('monto');
            $table->string('fecha_emicion');
            $table->string('fecha_vence');
            $table->string('metodo_pago');
            $table->decimal('pago_usd', 8, 2)->default(0.00);
            $table->decimal('pago_bsd', 8, 2)->default(0.00);
            $table->string('referencia');
            $table->string('responsable');
            $table->string('barcode');
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
