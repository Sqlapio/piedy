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
        Schema::create('membresias', function (Blueprint $table) {
            $table->id();
            $table->string('cod_membresia');
            $table->string('pm');
            $table->integer('cliente_id');
            $table->string('cliente');
            $table->string('fecha_compra');
            $table->decimal('monto',8 ,2)->nullable()->default(40);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('membresias');
    }
};