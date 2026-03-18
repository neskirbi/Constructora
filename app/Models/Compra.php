<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    use HasFactory;

    protected $table = 'compras';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id', 'id_contrato', 'id_usuario', 'id_proveedor', 'consecutivo',
        'clave_concepto', 'descripcion_concepto', 'unidad_concepto',
        'costo_unitario_concepto', 'cantidad', 'referencia', 'costo_operado',
        'iva', 'total', 'verificado', 'created_at', 'updated_at',
        'fecha_entrega', 'tipo_entrega', 'comentarios'
    ];
}