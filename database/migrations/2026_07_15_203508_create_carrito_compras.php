<?php
// database/migrations/YYYY_MM_DD_create_carrito_compras_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('carrito_compras', function (Blueprint $table) {
            $table->id();
            $table->string('session_id'); // Para identificar la sesión del usuario
            $table->foreignId('contrato_id')->nullable()->constrained('contratos')->onDelete('set null');
            $table->string('clave');
            $table->string('descripcion');
            $table->string('unidad');
            $table->decimal('cantidad', 15, 2);
            $table->decimal('precio_unitario', 15, 2)->nullable(); // Se llena desde el catálogo
            $table->decimal('subtotal', 15, 2)->nullable();
            $table->decimal('iva', 15, 2)->nullable();
            $table->decimal('total', 15, 2)->nullable();
            $table->string('observaciones')->nullable();
            $table->string('link')->nullable();
            $table->integer('fila_excel')->nullable(); // Para referenciar la fila original
            $table->timestamps();
            
            $table->index(['session_id', 'contrato_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('carrito_compras');
    }
};