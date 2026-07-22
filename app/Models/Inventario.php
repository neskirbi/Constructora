<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    protected $table = 'inventario'; // <--- Sin "s" al final
    
    protected $keyType = 'string';
    public $incrementing = false;
    
    protected $fillable = [
        'id',
        'clave',
        'descripcion',
        'unidad',
        'ult_costo',
    ];
}