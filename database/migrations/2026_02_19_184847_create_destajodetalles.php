<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDestajodetalles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('destajodetalles', function (Blueprint $table) {
           $table->string('id',32)->unique();
            $table->string('id_productoservicio',32);

            $table->string('clave',32);
            $table->text('descripcion');
            $table->string('unidades',10);
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
        Schema::dropIfExists('destajodetalles');
    }
}
