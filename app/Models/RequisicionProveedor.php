<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequisicionProveedor extends Model
{
    protected $table = 'requisicion_proveedores';
    
    protected $keyType = 'string';
    public $incrementing = false;
    
    protected $fillable = [
        'id',
        'requisicion_id',
        'proveedor_id',
        'monto',
    ];
    
    protected $casts = [
        'monto' => 'decimal:2',
    ];
    
    public function requisicion()
    {
        return $this->belongsTo(Requisicion::class, 'requisicion_id');
    }
    
    public function proveedor()
    {
        return $this->belongsTo(ProveedorSer::class, 'proveedor_id');
    }
}