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
        Schema::create('consumo_tecnicos', function (Blueprint $table) {
            $table->id();
            $table->integer('producto_id');
            $table->decimal('contenido', 8, 2);
            $table->string('unidad');
            $table->decimal('cant_servicio', 8, 2);
            $table->integer('total_uso');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consumo_tecnicos');
    }
};