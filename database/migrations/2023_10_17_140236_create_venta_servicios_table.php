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
            $table->integer('empleado_id')->nullable();
            $table->integer('servicio_id')->nullable();
            $table->string('fecha_venta')->nullable();
            $table->decimal('total', 5, 2)->nullable()->default(0.00);



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
