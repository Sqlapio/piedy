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
        Schema::create('periodo_nominas', function (Blueprint $table) {
            $table->id();
            $table->string('fecha_ini');
            $table->string('fecha_fin');
            $table->string('cod_quincena');
            $table->string('status')->default('1'); // 1 : abierto, 2 : cerrado
            $table->decimal('tasa_bcv', 8, 2)->default(0.00);
            $table->decimal('total_bolivares', 8, 2)->default(0.00);
            $table->decimal('total_dolares', 8, 2)->default(0.00);
            $table->decimal('total_general', 8, 2)->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periodo_nominas');
    }
};
