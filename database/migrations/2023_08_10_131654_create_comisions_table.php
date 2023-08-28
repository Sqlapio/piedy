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
        Schema::create('comisions', function (Blueprint $table) {
            $table->id();
            $table->string('cod_comision')->unique();
            $table->integer('empleado_id');
            $table->integer('ponderacion_id');
            $table->string('creada_por');
            $table->deciaml('porcentaje', 5, 2)->unique();
            $table->string('fecha_vence');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comisions');
    }
};
