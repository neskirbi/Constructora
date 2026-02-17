<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductosyservicios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productosyservicios', function (Blueprint $table) {
            $table->sting('id',32)->unique();

            $table->sting('clave',32);
            $table->text('descripcion');
            $table->sting('unidades',10);
            $table->float('ult_costo',20,2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('productosyservicios');
    }
}
