<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta la migración.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // Identificador único
            $table->string('name'); // Nombre del producto
            $table->text('description')->nullable(); // Descripción opcional
            $table->decimal('price', 10, 2); // Precio con hasta 10 dígitos y 2 decimales
            $table->integer('stock')->default(0); // Cantidad en inventario
            $table->unsignedBigInteger('user_id'); // Relación con el usuario que creó el producto
            $table->timestamps(); // Timestamps de Laravel
        });
    }

    /**
     * Revierte la migración.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
