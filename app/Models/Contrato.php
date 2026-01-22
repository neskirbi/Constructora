<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contrato extends Model
{
    use HasFactory;

    protected $table = 'contratos';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'obras',
        'empresa',
        'contrato_no',
        'frente',
        'gerencia',
        'cliente',
        'obra',
        'lugar',
        'importe',
        'iva',
        'total',
        'importe_convenio',
        'iva_convenio',
        'total_convenio',
        'importe_total',
        'iva_total',
        'total_total',
        'anticipo',
        'duracion',
        'contrato_fecha',
        'inicio_de_obra',
        'terminacion_de_obra',
        'observaciones',
        'razon_social',
        'rfc',
        'calle_y_numero',
        'colonia',
        'codigo_postal',
        'delegacion',
        'municipio',
        'telefono',
        'banco',
        'n_cuenta',
        'sucursal',
        'clabe_interbancaria',
        'mail_facturas',
        'representante_legal',
    ];

    protected $dates = [
        'contrato_fecha',
        'inicio_de_obra',
        'terminacion_de_obra',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'importe' => 'decimal:2',
        'iva' => 'decimal:2',
        'total' => 'decimal:2',
        'importe_convenio' => 'decimal:2',
        'iva_convenio' => 'decimal:2',
        'total_convenio' => 'decimal:2',
        'importe_total' => 'decimal:2',
        'iva_total' => 'decimal:2',
        'total_total' => 'decimal:2',
        'anticipo' => 'decimal:2',
    ];
}