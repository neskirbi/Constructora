<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingreso extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ingresos';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'id_contrato',
        'area',
        'estimacion',
        'periodo_del',
        'periodo_al',
        'factura',
        'fecha_factura',
        'importe_de_estimacion',
        'iva',
        'retenciones_o_sanciones',
        'total_estimacion_con_iva',
        'fecha_elaboracion',
        'avance_obra_estimacion',
        'avance_obra_real',
        'porcentaje_avance_financiero',
        'cargos_adicionales_35_porciento',
        'retencion_5_al_millar',
        'sancion_atraso_presentacion_estimacion',
        'sancion_atraso_de_obra',
        'sancion_por_obra_mal_ejecutada',
        'retencion_por_atraso_en_programa_de_obra',
        'amortizacion_anticipo',
        'amortizacion_con_iva',
        'total_deducciones',
        'importe_facturado',
        'liquido_a_cobrar',
        'liquido_cobrado',
        'fecha_cobro',
        'por_cobrar',
        'por_facturar',
        'status',
        'estimado_menos_deducciones',
        'verificado',
        // NOTA: Se eliminaron n_cuenta, sucursal y clabe_interbancaria
        // porque no existen en la tabla de la base de datos
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'periodo_del' => 'date',
        'periodo_al' => 'date',
        'fecha_factura' => 'date',
        'fecha_elaboracion' => 'date',
        'fecha_cobro' => 'date',
        'importe_de_estimacion' => 'decimal:2',
        'iva' => 'decimal:2',
        'retenciones_o_sanciones' => 'decimal:2',
        'total_estimacion_con_iva' => 'decimal:2',
        'avance_obra_estimacion' => 'decimal:2',
        'avance_obra_real' => 'decimal:2',
        'porcentaje_avance_financiero' => 'decimal:2',
        'cargos_adicionales_35_porciento' => 'decimal:2',
        'retencion_5_al_millar' => 'decimal:2',
        'sancion_atraso_presentacion_estimacion' => 'decimal:2',
        'sancion_atraso_de_obra' => 'decimal:2',
        'sancion_por_obra_mal_ejecutada' => 'decimal:2',
        'retencion_por_atraso_en_programa_de_obra' => 'decimal:2',
        'amortizacion_anticipo' => 'decimal:2',
        'amortizacion_con_iva' => 'decimal:2',
        'total_deducciones' => 'decimal:2',
        'importe_facturado' => 'decimal:2',
        'liquido_a_cobrar' => 'decimal:2',
        'liquido_cobrado' => 'decimal:2',
        'por_cobrar' => 'decimal:2',
        'por_facturar' => 'decimal:2',
        'estimado_menos_deducciones' => 'decimal:2',
        'verificado' => 'integer',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'verificado' => 1,
        'status' => 'pendiente',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = str_replace('-', '', \Illuminate\Support\Str::uuid());
            }
        });
    }

    /**
     * Obtener el contrato relacionado.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function contrato()
    {
        return $this->belongsTo(Contrato::class, 'id_contrato', 'id');
    }
}