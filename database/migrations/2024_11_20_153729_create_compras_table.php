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
        Schema::create('compras', function (Blueprint $table) {
            $table->id();
            $table->string('cod_compra');
            $table->unsignedBigInteger('proveedor_id');
            $table->string('descripcion');
            $table->string('monto_neto_usd');
            $table->string('monto_neto_bsd');
            $table->string('iva');
            $table->string('monto_con_iva');
            $table->string('forma_pago');
            $table->string('fecha_compra');
            $table->string('numero_factura_compra');
            $table->string('responsable');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compras');
    }
};
