<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompradetalle extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compradetalle', function (Blueprint $table) {
            $table->string('id', 32)->unique();
            $table->string('id_compra', 32);
            $table->string('id_productoservicio', 32);
            $table->string('clave', 32);
            $table->text('descripcion');
            $table->string('unidades', 10);
            $table->decimal('cantidad', 15, 2); 
            $table->decimal('descuento_porcentaje', 15, 2); 
            $table->decimal('descuento_monto', 15, 2); 
            $table->decimal('ult_costo', 20, 2); 
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
        Schema::dropIfExists('compradetalle');
    }
}
