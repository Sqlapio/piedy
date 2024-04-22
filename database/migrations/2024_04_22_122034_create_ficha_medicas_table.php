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
        Schema::create('ficha_medicas', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('cliente_id');
            /** Antecedentes Medicos */
            $table->boolean('am_p1')->nullable()->default(false);
            $table->boolean('am_p2')->nullable()->default(false);
            $table->boolean('am_p3')->nullable()->default(false);
            $table->boolean('am_p4')->nullable()->default(false);
            /** Historial Podologico */
            $table->boolean('hp_p1')->nullable()->default(false);
            $table->boolean('hp_p2')->nullable()->default(false);
            /** Estilo de Vida */
            $table->boolean('ev_p1')->nullable()->default(false);
            $table->boolean('ev_p2')->nullable()->default(false);
            $table->boolean('ev_p3')->nullable()->default(false);

            $table->string('comentario_adicional')->nullable()->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ficha_medicas');
    }
};
