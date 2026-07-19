<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Requisicion extends Model
{
    protected $table = 'requisiciones';
    
    protected $keyType = 'string';
    public $incrementing = false;
    
    protected $fillable = [
        'id',
        'session_id',
        'contrato_id',
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
        'fila_excel'
    ];
    
    protected $casts = [
        'cantidad' => 'decimal:2',
        'precio_unitario' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'iva' => 'decimal:2',
        'total' => 'decimal:2',
    ];
    
    public function contrato()
    {
        return $this->belongsTo(Contrato::class, 'contrato_id');
    }
    
    public function calcularTotales()
    {
        if ($this->precio_unitario && $this->cantidad) {
            $this->subtotal = $this->cantidad * $this->precio_unitario;
            $this->iva = $this->subtotal * 0.16;
            $this->total = $this->subtotal + $this->iva;
        }
        return $this;
    }
}