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
        Schema::create('detalle_asignacions', function (Blueprint $table) {
            $table->id();
            $table->string('cod_asignacion');
            $table->string("cod_servicio");
            $table->integer("empleado_id");
            $table->string("empleado");
            $table->integer("servicio_id");
            $table->string("servicio");
            $table->decimal('costo', 8, 2);
            $table->string("fecha");
            $table->integer("status")->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_asignacions');
    }
};
