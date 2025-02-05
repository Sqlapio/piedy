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
        Schema::create('consumibles', function (Blueprint $table) {
            $table->id();
            $table->integer('producto_id');
            $table->decimal('contenido_neto', 8,2);
            $table->string('unidad');
            $table->integer('can_srv');
            $table->integer('uso');
            $table->string('tipo_uso'); //En-tienda , Tecnicos
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consumibles');
    }
};