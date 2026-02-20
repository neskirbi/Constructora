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
        'referencia',
        'costo_operado',
        'iva',
        'total',
        'verificado'
    ];

    protected $casts = [
        'consecutivo' => 'integer',
        'costo_operado' => 'decimal:2',
        'iva' => 'decimal:2',
        'total' => 'decimal:2',
        'verificado' => 'integer',
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