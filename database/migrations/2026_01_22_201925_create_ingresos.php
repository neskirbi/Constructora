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
            $table->string('id',32)->unique();
            $table->string('id_contrato',32);
            $table->string('id_usuario',32)->default('');
            
             // Campos del Excel
            $table->string('no_estimacion')->nullable();
            $table->date('periodo_del')->nullable();
            $table->date('periodo_al')->nullable();
            $table->string('factura')->nullable();
            $table->date('fecha_factura')->nullable();
            $table->decimal('importe_estimacion', 15, 2)->default(0);
            $table->decimal('iva', 15, 2)->default(0);
            $table->decimal('retenciones_o_sanciones', 15, 2)->default(0);
            $table->decimal('total_estimacion_con_iva', 15, 2)->default(0);
            $table->decimal('avance_obra_estimacion', 10, 2)->default(0);
            $table->decimal('avance_obra_real', 10, 2)->default(0);
            $table->decimal('porcentaje_avance_financiero', 10, 2)->default(0);
            $table->decimal('cargos_adicionales_3_5', 15, 2)->default(0);
            $table->decimal('retencion_5_al_millar', 15, 2)->default(0);
            $table->decimal('sancion_atrazo_presentacion_estimacion', 15, 2)->default(0);
            $table->decimal('sancion_atraso_de_obra', 15, 2)->default(0);
            $table->decimal('sancion_por_obra_mal_ejecutada', 15, 2)->default(0);
            $table->decimal('retencion_por_atraso_en_programa_obra', 15, 2)->default(0);
            $table->decimal('amortizacion_anticipo', 15, 2)->default(0);
            $table->decimal('amortizacion_con_iva', 15, 2)->default(0);
            $table->decimal('total_deducciones', 15, 2)->default(0);
            $table->decimal('importe_facturado', 15, 2)->default(0);
            $table->decimal('liquido_a_cobrar', 15, 2)->default(0);
            $table->decimal('liquido_cobrado', 15, 2)->default(0);
            $table->date('fecha_cobro')->nullable();
            $table->decimal('por_cobrar', 15, 2)->default(0);
            $table->decimal('por_facturar', 15, 2)->default(0);
            $table->decimal('por_estimar', 15, 2)->default(0);
            $table->string('status')->nullable();
            $table->decimal('estimado_menos_deducciones', 15, 2)->default(0);

            
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
        Schema::table('ingresos', function (Blueprint $table) {
            //
        });
    }
}
