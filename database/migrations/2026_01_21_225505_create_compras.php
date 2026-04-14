<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompras extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compras', function (Blueprint $table) {
            $table->string('id',32)->unique();
            $table->string('id_contrato',32);            
            $table->string('id_usuario',32);
            $table->string('id_proveedor',32);
            
            $table->integer('consecutivo')->nullable();
            
            $table->string('referencia', 1500)->nullable();
            $table->decimal('costo_operado', 15, 2)->nullable();
            $table->decimal('iva', 15, 2)->nullable();
            $table->decimal('total', 15, 2)->nullable();

            $table->string('metodo_pago', 50)->nullable();
            $table->string('empresa_pago', 255)->nullable();
            
            $table->integer('verificado')->default(1);
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
        Schema::dropIfExists('compras');
    }
}