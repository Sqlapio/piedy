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
        Schema::create('venta_servicios', function (Blueprint $table) {
            $table->id();
            $table->string('cod_asignacion')->unique();
            $table->string('empleado');
            $table->integer('empleado_id');
            $table->string('cliente');
            $table->integer('cliente_id');
            $table->string('metodo_pago')->nullable();
            $table->string('metodo_pago_dos')->nullable();
            $table->string('referencia')->nullable();
            $table->string('fecha_venta');
            $table->decimal('comision_empleado', 8, 2)->default(0.00);
            $table->decimal('comision_gerente', 8, 2)->default(0.00);
            $table->decimal('total_USD', 8, 2)->default(0.00);
            $table->decimal('pago_usd', 8, 2)->default(0.00);
            $table->decimal('pago_bsd', 8, 2)->default(0.00);
            $table->decimal('propina_usd', 8, 2)->default(0.00);
            $table->decimal('propina_bsd', 8, 2)->default(0.00);
            $table->string('referencia_propina')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venta_servicios');
    }
};
