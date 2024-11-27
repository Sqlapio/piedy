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
        Schema::create('recepcion_inventarios', function (Blueprint $table) {
            $table->id();
            $table->integer('inventario_id');
            $table->integer('producto_id');
            $table->integer('sucursal_id');
            $table->integer('cantidad')->default(0);
            $table->string('responsable');
            $table->string('status');
            $table->date('accepted_at');
            $table->string('uso');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recepcion_inventarios');
    }
};