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
        Schema::create('cosumo_mensuals', function (Blueprint $table) {
            $table->id();
            $table->string('empleado');
            $table->string('producto');
            $table->string('cantidad_total');
            $table->string('unidad');
            $table->string('total_servicios');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cosumo_mensuals');
    }
};
