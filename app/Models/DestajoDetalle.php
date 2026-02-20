<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DestajoDetalle extends Model
{
    use HasFactory;

    protected $table = 'destajodetalles';
    
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'id_destajo',
        'id_productoservicio',
        'clave',
        'descripcion',
        'unidades',
        'cantidad',
        'ult_costo',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'cantidad' => 'decimal:2',
        'ult_costo' => 'decimal:2'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = GetUuid();
            }
        });
    }
}