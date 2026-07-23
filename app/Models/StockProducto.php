<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockProducto extends Model
{
    protected $table = 'stock_productos';
    
    protected $keyType = 'string';
    public $incrementing = false;
    
    protected $fillable = [
        'id',
        'producto_id',
        'cantidad',
        'minimo',
        'maximo',
        'ubicacion',
    ];
    
    protected $casts = [
        'cantidad' => 'decimal:2',
        'minimo' => 'decimal:2',
        'maximo' => 'decimal:2',
    ];
    
    public function producto()
    {
        return $this->belongsTo(ProductoServicio::class, 'producto_id');
    }
}