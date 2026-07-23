<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('stock_productos', function (Blueprint $table) {
            $table->string('id', 32)->primary();
            $table->string('producto_id', 32);
            $table->decimal('cantidad', 15, 2)->default(0);
            $table->decimal('minimo', 15, 2)->default(0);
            $table->decimal('maximo', 15, 2)->default(0);
            $table->string('ubicacion')->nullable();
            $table->timestamps();
            
            $table->foreign('producto_id')
                  ->references('id')
                  ->on('productosyservicios')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('stock_productos');
    }
};