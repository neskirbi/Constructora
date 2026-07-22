<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequisicionDetalle extends Model
{
    protected $table = 'requisicion_detalle';
    
    protected $keyType = 'string';
    public $incrementing = false;
    
    protected $fillable = [
        'id',
        'requisicion_id',
        'clave',
        'descripcion',
        'unidad',
        'cantidad',
        'precio_unitario',
        'subtotal',
        'iva',
        'total',
        'observaciones',
        'link',
    'proveedor_id',      // <--- Agrega
    'descuento',         // <--- Agrega
    'descuento_monto',   // <--- Agrega
    ];
    
    protected $casts = [
    'cantidad' => 'decimal:2',
    'precio_unitario' => 'decimal:2',
    'subtotal' => 'decimal:2',
    'iva' => 'decimal:2',
    'total' => 'decimal:2',
    'descuento' => 'decimal:2',
    'descuento_monto' => 'decimal:2',
];
    
    public function requisicion()
    {
        return $this->belongsTo(Requisicion::class, 'requisicion_id');
    }

    public function proveedores()
    {
        return $this->hasMany(ProductoProveedor::class, 'detalle_id');
    }

    public function proveedorSeleccionado()
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }

    
}