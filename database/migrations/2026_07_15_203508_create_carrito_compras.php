<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('requisiciones', function (Blueprint $table) {
            $table->string('id', 32)->primary();
            $table->string('session_id');
            $table->string('contrato_id', 32)->nullable();
            $table->string('clave', 50);
            $table->string('descripcion', 255);
            $table->string('unidad', 20);
            $table->decimal('cantidad', 15, 2);
            $table->decimal('precio_unitario', 15, 2)->nullable();
            $table->decimal('subtotal', 15, 2)->nullable();
            $table->decimal('iva', 15, 2)->nullable();
            $table->decimal('total', 15, 2)->nullable();
            $table->text('observaciones')->nullable();
            $table->string('link', 255)->nullable();
            $table->integer('fila_excel')->nullable();
            $table->timestamps();
            
            $table->index(['session_id', 'contrato_id']);
            $table->foreign('contrato_id')->references('id')->on('contratos')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('requisiciones');
    }
};