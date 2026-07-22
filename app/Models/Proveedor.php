<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table = 'proveedores';
    
    protected $keyType = 'string';
    public $incrementing = false;
    
    protected $fillable = [
        'id',
        'clave',
        'nombre',
        'calle',
        'telefono',
        'clasificacion',
        'especialidad',
        'estatus',
    ];
    
    protected $casts = [
        'estatus' => 'boolean',
    ];
    
    public function requisiciones()
    {
        return $this->hasMany(RequisicionProveedor::class, 'proveedor_id');
    }
}