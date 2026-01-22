<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class ADestajo extends Authenticatable
{
    use HasFactory, Notifiable;
    
    protected $table = 'adestajos';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = GetUuid();
            }
        });
    }
    
    protected $fillable = [
        'id', 'nombres', 'apellidos', 'mail', 'pass', 'passtemp', 'principal'
    ];
    
    protected $hidden = ['password', 'remember_token'];
}