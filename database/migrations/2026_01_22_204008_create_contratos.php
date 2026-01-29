<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContratos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contratos', function (Blueprint $table) {
            $table->string('id',32)->unique();
            $table->string('id_usuario',32)->default('');
            
             // Columnas del Excel
            $table->string('empresa')->nullable();
            $table->string('contrato_no')->nullable();
            $table->string('frente')->nullable();
            $table->string('gerencia')->nullable();
            $table->string('cliente')->nullable();
            $table->string('obra')->nullable();
            $table->string('lugar')->nullable();
            $table->string('concepto',50)->nullable();
            $table->decimal('subtotal', 15, 2)->nullable();
            $table->decimal('iva', 15, 2)->nullable();
            $table->decimal('total', 15, 2)->nullable();
            $table->decimal('monto_anticipo', 15, 2)->nullable();
            $table->string('duracion')->nullable();
            $table->date('fecha_contrato')->nullable();
            $table->date('fecha_inicio_obra')->nullable();
            $table->date('fecha_terminacion_obra')->nullable();
            $table->text('observaciones')->nullable();
            $table->string('razon_social')->nullable();
            $table->string('rfc')->nullable();
            $table->string('calle_numero')->nullable();
            $table->string('colonia')->nullable();
            $table->string('codigo_postal')->nullable();
            $table->string('entidad')->nullable();
            $table->string('alcaldia_municipio')->nullable();
            $table->string('telefono')->nullable();            
            $table->decimal('latitud', 10, 8)->nullable();
            $table->decimal('longitud', 11, 8)->nullable();
            $table->string('banco')->nullable();
            $table->string('no_cuenta')->nullable();
            $table->string('sucursal')->nullable();
            $table->string('clabe_interbancaria')->nullable();
            $table->string('mail_facturas')->nullable();
            $table->string('representante_legal')->nullable();

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
        Schema::dropIfExists('contratos');
    }
}
