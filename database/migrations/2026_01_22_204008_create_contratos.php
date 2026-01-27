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
            $table->text('obra')->nullable();
            $table->string('empresa')->nullable();
            $table->string('contrato_no')->nullable();
            $table->string('frente')->nullable();
            $table->string('gerencia')->nullable();
            $table->string('cliente')->nullable();
            $table->string('lugar')->nullable();
            $table->decimal('importe_contrato', 15, 2)->nullable();
            $table->decimal('iva_contrato', 15, 2)->nullable();
            $table->decimal('total_contrato', 15, 2)->nullable();
            $table->decimal('importe_convenio', 15, 2)->nullable();
            $table->decimal('iva_convenio', 15, 2)->nullable();
            $table->decimal('total_convenio', 15, 2)->nullable();
            $table->decimal('importe_total', 15, 2)->nullable();
            $table->decimal('iva_total', 15, 2)->nullable();
            $table->decimal('total_total', 15, 2)->nullable();
            $table->decimal('anticipo', 15, 2)->nullable();
            $table->string('duracion')->nullable();
            $table->date('contrato_fecha')->nullable();
            $table->date('inicio_de_obra')->nullable();
            $table->date('terminacion_de_obra')->nullable();
            $table->text('observaciones')->nullable();
            $table->string('razon_social')->nullable();
            $table->string('rfc', 20)->nullable();
            $table->string('calle_y_numero')->nullable();
            $table->string('colonia')->nullable();
            $table->string('codigo_postal', 10)->nullable();
            $table->string('delegacion')->nullable();
            $table->string('municipio')->nullable();
            $table->string('telefono')->nullable();
            $table->decimal('latitud', 10, 8)->nullable();
            $table->decimal('longitud', 11, 8)->nullable();
            
            $table->string('banco')->nullable();
            $table->string('n_cuenta')->nullable();
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
