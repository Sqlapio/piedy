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
        Schema::create('promocions', function (Blueprint $table) {
            $table->id();
            $table->string('cod_promocion');
            $table->string('fecha_inicio');
            $table->string('fecha_fin');
            $table->string('tipo');
            $table->decimal('porcentaje', 8, 2)->nullable()->default(0.00);
            $table->string('status');
            $table->string('image', 2048)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promocions');
    }
};
