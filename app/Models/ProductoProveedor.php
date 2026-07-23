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
        'seleccionado', 
        'fecha_entrega',
    ];
    
    protected $casts = [
        'precio' => 'decimal:2',
        'descuento' => 'decimal:2',
        'seleccionado' => 'boolean',
        'fecha_entrega' => 'date',  // <--- Agregar
    ];
    
    public function detalle()
    {
        return $this->belongsTo(RequisicionDetalle::class, 'detalle_id');
    }
    
    public function proveedor()
    {
        return $this->belongsTo(ProveedorSer::class, 'proveedor_id');
    }
}