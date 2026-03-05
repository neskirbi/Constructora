<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Ingreso extends Model
{
    use HasFactory;

    protected $table = 'ingresos';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'id_contrato',
        'id_usuario',
        'no_estimacion',
        'periodo_del',
        'periodo_al',
        'factura',
        'fecha_factura',
        'importe_estimacion',
        'iva',
        'importe_iva', // ← NUEVO
        'retenciones_o_sanciones',
        'total_estimacion_con_iva',
        'sicv_cop', // ← NUEVO
        'srcop_cdmx', // ← NUEVO
        'retencion_5_al_millar',
        'sancion_atrazo_presentacion_estimacion',
        'sancion_atraso_de_obra',
        'sancion_por_obra_mal_ejecutada',
        'retencion_por_atraso_en_programa_obra',
        'amortizacion_anticipo',
        'amortizacion_iva', // ← CAMBIADO (antes amortizacion_con_iva)
        'total_amortizacion', // ← NUEVO
        'liquido_a_cobrar',
        'liquido_cobrado',
        'fecha_cobro',
        'por_cobrar',
        'status',
        'estimado_menos_deducciones',
        'verificado',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'periodo_del' => 'date:Y-m-d',
        'periodo_al' => 'date:Y-m-d',
        'fecha_factura' => 'date:Y-m-d',
        'fecha_cobro' => 'date:Y-m-d',
        'importe_estimacion' => 'decimal:2',
        'iva' => 'decimal:2',
        'importe_iva' => 'decimal:2',
        'retenciones_o_sanciones' => 'decimal:2',
        'total_estimacion_con_iva' => 'decimal:2',
        'sicv_cop' => 'decimal:2',
        'srcop_cdmx' => 'decimal:2',
        'retencion_5_al_millar' => 'decimal:2',
        'sancion_atrazo_presentacion_estimacion' => 'decimal:2',
        'sancion_atraso_de_obra' => 'decimal:2',
        'sancion_por_obra_mal_ejecutada' => 'decimal:2',
        'retencion_por_atraso_en_programa_obra' => 'decimal:2',
        'amortizacion_anticipo' => 'decimal:2',
        'amortizacion_iva' => 'decimal:2',
        'total_amortizacion' => 'decimal:2',
        'liquido_a_cobrar' => 'decimal:2',
        'liquido_cobrado' => 'decimal:2',
        'por_cobrar' => 'decimal:2',
        'estimado_menos_deducciones' => 'decimal:2',
        'verificado' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}