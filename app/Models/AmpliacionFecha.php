<?php
// app/Models/AmpliacionFecha.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmpliacionFecha extends Model
{
    use HasFactory;

    protected $table = 'ampliacionestiempo';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'id_contrato',
        'fecha_terminacion_obra'
    ];

    protected $casts = [
        'fecha_terminacion_obra' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
}