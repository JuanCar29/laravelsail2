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
        Schema::create('lineas_recibos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recibo_id')->constrained()->restrictOnDelete();
            $table->foreignId('producto_id')->constrained()->restrictOnDelete(); // No eliminar si hay ventas
            $table->string('nombre_producto'); // Copia del nombre en el momento de la venta (por inmutabilidad)
            $table->integer('cantidad');
            $table->decimal('precio_unitario', 10, 2); // Precio al momento de la venta
            $table->decimal('subtotal', 10, 2); // cantidad * precio_unitario
            $table->decimal('iva', 10, 2);
            $table->boolean('listo')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lineas_recibos');
    }
};
