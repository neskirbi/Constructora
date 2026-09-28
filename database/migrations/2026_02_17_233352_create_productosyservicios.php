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
            $table->string('id', 32)->unique();

            $table->string('clave', 32);
            $table->text('descripcion');
            $table->string('unidades', 10);
            $table->float('ult_costo', 20, 2);

            // Precio (ya lo tenías en la BD real)
            $table->decimal('precio', 20, 2)->default(0.00);

            // NUEVO: rutas de los PDF (opcionales)
            $table->string('archivo_pdf_1', 255)->nullable()->after('precio');
            $table->string('archivo_pdf_2', 255)->nullable()->after('archivo_pdf_1');

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