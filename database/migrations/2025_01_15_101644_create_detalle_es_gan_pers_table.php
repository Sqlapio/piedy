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
        Schema::create('detalle_es_gan_pers', function (Blueprint $table) {
            $table->id();
            $table->decimal('ingresos_usd', 10, 2)->default(0);
            $table->decimal('ingresos_bsd', 10, 2)->default(0);
            $table->decimal('mano_de_obra', 10, 2)->default(0);
            $table->decimal('otros_costos_directos', 10, 2)->default(0);
            $table->decimal('publicidad', 10, 2)->default(0);
            $table->decimal('comisiones_empleados', 10, 2)->default(0);
            $table->decimal('sueldos_empleados', 10, 2)->default(0);
            $table->decimal('alquiler', 10, 2)->default(0);
            $table->decimal('telefono', 10, 2)->default(0);
            $table->decimal('internet', 10, 2)->default(0);
            $table->decimal('gastos_financieros', 10, 2)->default(0);
            $table->decimal('perdidas_no_recurrentes', 10, 2)->default(0);
            $table->decimal('ingresos_financieros', 10, 2)->default(0);
            $table->decimal('ganancias_no_recurrentes', 10, 2)->default(0);
            $table->string('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_es_gan_pers');
    }
};
