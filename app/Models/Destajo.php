<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destajo extends Model
{
    use HasFactory;

    protected $table = 'destajos';

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'id_contrato',
        'id_usuario',
        'id_proveedor',
        'consecutivo',
        'clave_concepto',
        'descripcion_concepto',
        'unidad_concepto',
        'costo_unitario_concepto',
        'cantidad',
        'referencia',
        'costo_operado',
        'iva',
        'total',
    ];

    protected $casts = [
        'consecutivo' => 'integer',
        'costo_unitario_concepto' => 'decimal:2',
        'cantidad' => 'decimal:2',
        'costo_operado' => 'decimal:2',
        'iva' => 'decimal:2',
        'total' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = GetUuid();
            }
        });
    }
}