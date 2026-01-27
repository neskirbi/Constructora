<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIngresosTable extends Migration
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
            $table->string('area')->nullable();  
            $table->string('estimacion')->nullable();
            $table->date('periodo_del')->nullable();
            $table->date('periodo_al')->nullable();
            $table->string('factura')->nullable();
            $table->date('fecha_factura')->nullable();
            $table->decimal('importe_de_estimacion', 15, 2)->nullable();
            $table->decimal('iva', 15, 2)->nullable();
            $table->decimal('retenciones_o_sanciones', 15, 2)->nullable();
            $table->decimal('total_estimacion_con_iva', 15, 2)->nullable();
            $table->date('fecha_elaboracion')->nullable();
            $table->decimal('avance_obra_estimacion', 15, 2)->nullable();
            $table->decimal('avance_obra_real', 15, 2)->nullable();
            $table->decimal('porcentaje_avance_financiero', 15, 2)->nullable();
            $table->decimal('cargos_adicionales_35_porciento', 15, 2)->nullable();
            $table->decimal('retencion_5_al_millar', 15, 2)->nullable();
            $table->decimal('sancion_atraso_presentacion_estimacion', 15, 2)->nullable();
            $table->decimal('sancion_atraso_de_obra', 15, 2)->nullable();
            $table->decimal('sancion_por_obra_mal_ejecutada', 15, 2)->nullable();
            $table->decimal('retencion_por_atraso_en_programa_de_obra', 15, 2)->nullable();
            $table->decimal('amortizacion_anticipo', 15, 2)->nullable();
            $table->decimal('amortizacion_con_iva', 15, 2)->nullable();
            $table->decimal('total_deducciones', 15, 2)->nullable();
            $table->decimal('importe_facturado', 15, 2)->nullable();
            $table->decimal('liquido_a_cobrar', 15, 2)->nullable();
            $table->decimal('liquido_cobrado', 15, 2)->nullable();
            $table->date('fecha_cobro')->nullable();
            $table->decimal('por_cobrar', 15, 2)->nullable();
            $table->decimal('por_facturar', 15, 2)->nullable();
            $table->string('status')->nullable();
            $table->decimal('estimado_menos_deducciones', 15, 2)->nullable();
            $table->string('n_cuenta')->nullable();
            $table->string('sucursal')->nullable();
            $table->string('clabe_interbancaria')->nullable();
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
