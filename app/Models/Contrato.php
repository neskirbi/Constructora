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
    
    protected $fillable = [
        'id',
        'consecutivo',
        'id_usuario',
        'empresa',
        'refinterna',
        'contrato_no',
        'frente',
        'gerencia',
        'cliente',
        'obra',
        'lugar',
        'concepto',
        'subtotal',
        'iva',
        'total',
        'monto_anticipo',
        'duracion',
        'fecha_contrato',
        'fecha_inicio_obra',
        'fecha_terminacion_obra',
        'observaciones',
        'razon_social',
        'rfc',
        'calle_numero',
        'colonia',
        'codigo_postal',
        'entidad',
        'alcaldia_municipio',
        'telefono',
        'latitud',
        'longitud',
        'banco',
        'no_cuenta',
        'sucursal',
        'clabe_interbancaria',
        'mail_facturas',
        'representante_legal',
        'created_at',
        'updated_at'
    ];

    // Agrega esto para convertir las fechas a Carbon
    protected $casts = [
        'fecha_contrato' => 'date',
        'fecha_inicio_obra' => 'date',
        'fecha_terminacion_obra' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'subtotal' => 'decimal:2',
        'iva' => 'decimal:2',
        'total' => 'decimal:2',
        'monto_anticipo' => 'decimal:2',
        'latitud' => 'decimal:8',
        'longitud' => 'decimal:8',
    ];
}