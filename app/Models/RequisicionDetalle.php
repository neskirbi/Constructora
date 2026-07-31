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
        'producto_id',
        'clave',
        'descripcion',
        'unidad',
        'partida',
        'cantidad',
        'cantidad_comprar',
        'inventario',
        'unidad_compra',
        'observaciones',
        'link',
    ];
    
    protected $casts = [
        'cantidad' => 'decimal:2',
    ];
    
    public function requisicion()
    {
        return $this->belongsTo(Requisicion::class, 'requisicion_id');
    }
    
    public function producto()
    {
        return $this->belongsTo(ProductoServicio::class, 'producto_id');
    }
    
    public function proveedores()
    {
        return $this->hasMany(ProductoProveedor::class, 'detalle_id');
    }
}