<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProveedoresServicios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proveedores_servicios', function (Blueprint $table) {
            $table->string('id',32)->unique();

            $table->string('clave', 50);
            $table->string('estatus', 50);
            $table->string('nombre', 255);
            $table->string('calle', 255);
            $table->string('telefono', 20);
            $table->string('clasificacion', 100);
            $table->string('especialidad', 255);

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
        Schema::dropIfExists('proveedores_servicios');
    }
}
