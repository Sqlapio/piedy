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
        Schema::create('nomina_generals', function (Blueprint $table) {
            $table->id();
            $table->string('fecha');
            $table->string('quincena', 8, 2);
            $table->string('cod_quincena', 8, 2);
            $table->decimal('total_bolivares', 8, 2)->default(0.00);
            $table->decimal('total_dolares', 8, 2)->default(0.00);
            $table->decimal('total_general', 8, 2)->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nomina_generals');
    }
};
