<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDestajos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('destajos', function (Blueprint $table) {
            $table->string('id',32)->unique();
            $table->string('id_contrato',32);            
            $table->string('id_usuario',32);
            $table->string('id_proveedor',32);
          
            
            $table->integer('consecutivo')->nullable();
            
            $table->string('clave_concepto', 50)->nullable();
            $table->text('descripcion_concepto')->nullable();
            $table->string('unidad_concepto', 50)->nullable();
            $table->decimal('costo_unitario_concepto', 15, 2)->nullable();
            $table->decimal('cantidad', 15, 2)->nullable();
            $table->string('referencia', 1500)->nullable();
            $table->decimal('costo_operado', 15, 2)->nullable();
            $table->decimal('iva', 15, 2)->nullable();
            $table->decimal('total', 15, 2)->nullable();
            
            
            $table->integer('verificado')->default(1);
            $table->timestamps();
            
            // Índices opcionales para mejor rendimiento en búsquedas frecuentes
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('destajos');
    }
}
