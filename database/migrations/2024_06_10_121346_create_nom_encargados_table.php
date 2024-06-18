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
        Schema::create('nom_encargados', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('name', 100);
            $table->integer('total_servicios', 100);
            $table->decimal('total_comision_dolares', 8, 2);
            $table->decimal('asignaciones_bolivares', 8, 2)->default(0.00);
            $table->decimal('deducciones_dolares', 8, 2)->default(0.00);
            $table->decimal('salario_quincenal', 8, 2)->default(0.00);
            $table->string('fecha_ini');
            $table->string('fecha_fin');
            $table->decimal('total_dolares', 8, 2)->default(0.00);
            $table->decimal('total_bolivares', 8, 2)->default(0.00);
            $table->string('quincena', 8, 2);
            $table->string('cod_quincena', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nom_encargados');
    }
};
