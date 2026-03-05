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
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->foreignId('categoria_id')->constrained()->restrictOnDelete();
            $table->decimal('precio', 8, 2);
            $table->foreignId('iva_id')->constrained()->restrictOnDelete();
            $table->boolean('destacado')->default(false);
            $table->boolean('activo')->default(true);
            $table->integer('orden')->nullable();
            $table->string('imagen')->nullable();
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
