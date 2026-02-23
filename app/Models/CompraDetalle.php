<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompraDetalle extends Model
{
    use HasFactory;

    protected $table = 'compradetalle';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id', 'id_compra', 'id_productoservicio', 'clave', 'descripcion',
        'unidades', 'cantidad', 'ult_costo', 'created_at', 'updated_at'
    ];
}