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
        Schema::create('nom_manicuristas', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('name', 100);
            $table->integer('total_servicios');
            $table->decimal('promedio_duracion_servicios', 8, 2);
            $table->decimal('total_comision_dolares', 8, 2);
            $table->decimal('total_comision_bolivares', 8, 2);
            $table->decimal('asignaciones_dolares', 8, 2)->default(0.00);
            $table->decimal('asignaciones_bolivares', 8, 2)->default(0.00);
            $table->decimal('deducciones_dolares', 8, 2)->default(0.00);
            $table->decimal('deducciones_bolivares', 8, 2)->default(0.00);
            $table->string('fecha_ini');
            $table->string('fecha_fin');
            $table->decimal('total_nomina', 8, 2)->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nom_manicuristas');
    }
};
