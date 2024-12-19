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
        Schema::create('pre_nominas', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('rol_id');
            $table->integer('sucursal_id');
            $table->integer('total_servicios');
            $table->decimal('comision_usd', 8, 2)->default(0.00);
            $table->decimal('comision_bsd', 8, 2)->default(0.00);
            $table->decimal('comision_prod', 8, 2)->default(0.00);
            $table->decimal('propinas_usd', 8, 2)->default(0.00);
            $table->decimal('propinas_bsd', 8, 2)->default(0.00);            
            $table->decimal('asignaciones_usd', 8, 2)->default(0.00);
            $table->decimal('asignaciones_bsd', 8, 2)->default(0.00);
            $table->decimal('deducciones_usd', 8, 2)->default(0.00);
            $table->decimal('deducciones_bsd', 8, 2)->default(0.00);
            $table->string('fecha_ini');
            $table->string('fecha_fin');
            $table->decimal('total_usd', 8, 2)->default(0.00);
            $table->decimal('total_bsd', 8, 2)->default(0.00);
            $table->string('status')->default('1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pre_nominas');
    }
};