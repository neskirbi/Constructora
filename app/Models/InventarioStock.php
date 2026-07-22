<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventarioStock extends Model
{
    protected $table = 'inventario_stock';
    
    protected $keyType = 'string';
    public $incrementing = false;
    
    protected $fillable = [
        'id',
        'inventario_id',
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
    
    public function inventario()
    {
        return $this->belongsTo(Inventario::class, 'inventario_id');
    }
}