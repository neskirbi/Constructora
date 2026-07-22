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
        'consecutivo',
        'fecha_solicitud',
        'fecha_entrega',
        'frente',
        'contrato_id',
        'empresa',
        'proyecto',
        'cliente',
        'especialidad',
        'direccion_entrega',
        'contratista',
        'partida',
        'session_id',
    ];
    
    protected $casts = [
        'fecha_solicitud' => 'date',
        'fecha_entrega' => 'date',
    ];
    
    public function contrato()
    {
        return $this->belongsTo(Contrato::class, 'contrato_id');
    }
    
    public function detalles()
    {
        return $this->hasMany(RequisicionDetalle::class, 'requisicion_id');
    }
    
    public function proveedores()
    {
        return $this->hasMany(RequisicionProveedor::class, 'requisicion_id');
    }
}