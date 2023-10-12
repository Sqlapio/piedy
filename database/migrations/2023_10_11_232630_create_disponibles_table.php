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
        Schema::create('disponibles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cliente_id');
            $table->unsignedBigInteger('empleado_id')->nullable();
            $table->unsignedBigInteger('servicio_id');
            $table->string('costo');
            $table->string('duracion');
            $table->string('tiempo_restante')->nullable();
            $table->string('cabina')->nullable();
            $table->string('status')->default('espera');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disponibles');
    }
};
