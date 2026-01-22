<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIngresos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ingresos', function (Blueprint $table) {
            
            $table->string('id', 32)->primary();
            
            // Código interno de la empresa para la obra
            $table->string('codigo_obra', 50)->unique();
            
            // Información básica de la obra/licitación
            $table->string('nombre_obra', 200);
            $table->text('descripcion')->nullable();
            $table->string('cliente', 150);
            $table->string('ubicacion', 200);
            
            // Datos económicos
            $table->decimal('presupuesto', 15, 2)->nullable();
            $table->decimal('monto_adjudicado', 15, 2)->nullable();
            
            // Fechas importantes
            $table->date('fecha_licitacion')->nullable();
            $table->date('fecha_adjudicacion')->nullable();
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin_estimada')->nullable();
            $table->date('fecha_fin_real')->nullable();
            
            // Estado y categoría
            $table->enum('estado', [
                'licitacion',
                'adjudicada', 
                'en_ejecucion',
                'suspendida',
                'finalizada',
                'cancelada'
            ])->default('licitacion');
            
            $table->string('categoria', 100)->default('');
            
            // Progreso y observaciones
            $table->integer('progreso')->default(0);
            $table->text('observaciones')->dafault('');
            
            // Relaciones (ajustar según tu estructura de usuarios/empleados)
            $table->string('responsable_id',32)->defaul('');
            $table->string('sucursal_id',32)->default('');
            
            // Timestamps
            $table->timestamps();
            $table->softDeletes();
            
            // Índices
            $table->index('codigo_obra');
            $table->index('estado');
            $table->index('cliente');
            $table->index('fecha_inicio');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ingresos');
    }
}