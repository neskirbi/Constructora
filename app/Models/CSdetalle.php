<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CSdetalle extends Model
{
    use HasFactory;

    protected $table = 'productosyservicios';
    
    protected $primaryKey = 'id';
    
    public $incrementing = false;
    
    protected $keyType = 'string';
    
    protected $fillable = [
        'id',
        'id_productoservicio',
        'clave',
        'descripcion',
        'unidades',
        'ult_costo'
    ];
}