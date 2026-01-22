<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcompras extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acompras', function (Blueprint $table) {
            $table->string('id', 32)->primary();
            $table->string('nombres', 150);
            $table->string('apellidos', 150);
            $table->string('mail', 50);
            $table->string('pass', 255);
            $table->string('passtemp', 20)->default('');
            $table->timestamps();
            
            // Índice para búsquedas por email
            $table->index('mail');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('acompras');
    }
}
