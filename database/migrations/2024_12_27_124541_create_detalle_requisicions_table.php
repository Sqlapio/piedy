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
        Schema::create('detalle_requisicions', function (Blueprint $table) {
            $table->id();
            $table->string('codigo');
            $table->unsignedInteger('requisicion_id');
            $table->unsignedInteger('sucursal_id');
            $table->unsignedInteger('producto_id');
            $table->string('uso');
            $table->integer('cantidad');
            $table->unsignedInteger('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_requisicions');
    }
};