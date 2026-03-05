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
        Schema::create('recibos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->string('codigo')->unique();
            $table->enum('estado', ['abierto', 'efectivo', 'tarjeta', 'pendiente'])->default('abierto');
            $table->enum('cocina', ['pendiente', 'cocinando', 'listo'])->nullable();
            $table->enum('lugar', ['barra', 'mesa', 'terraza', 'recoger'])->default('barra');
            $table->integer('mesa')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recibos');
    }
};
