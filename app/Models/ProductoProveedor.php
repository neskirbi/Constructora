<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductoProveedor extends Model
{
    protected $table = 'producto_proveedores';
    
    protected $keyType = 'string';
    public $incrementing = false;
    
    protected $fillable = [
        'id',
        'detalle_id',
        'proveedor_id',
        'precio',
        'descuento',
    ];
    
    protected $casts = [
        'precio' => 'decimal:2',
        'descuento' => 'decimal:2',
    ];
    
    public function detalle()
    {
        return $this->belongsTo(RequisicionDetalle::class, 'detalle_id');
    }
    
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }
}