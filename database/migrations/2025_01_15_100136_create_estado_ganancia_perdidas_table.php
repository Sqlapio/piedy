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
        Schema::create('estado_ganancia_perdidas', function (Blueprint $table) {
            $table->id();
            $table->decimal('ingresos_usd', 10, 2)->default(0);
            $table->decimal('ingresos_bsd', 10, 2)->default(0);
            $table->decimal('ingresos_totales_usd', 10, 2)->default(0);
            $table->decimal('costos_directos_usd', 10, 2)->default(0);
            $table->decimal('margen_bruto', 10, 2)->default(0);
            $table->decimal('gastos_operativos', 10, 2)->default(0);
            $table->decimal('gastos_no_operativos', 10, 2)->default(0);
            $table->decimal('utilidad_operativa_usd', 10, 2)->default(0);
            $table->decimal('utilidad_antes_impuestos', 10, 2)->default(0);
            $table->decimal('impuesto', 10, 2)->default(0);
            $table->decimal('utilidad_neta', 10, 2)->default(0);
            $table->string('notas_explicitas')->nullable();
            $table->string('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estado_ganancia_perdidas');
    }
};
