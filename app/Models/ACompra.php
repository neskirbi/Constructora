<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class ACompra extends Authenticatable
{
    use HasFactory, Notifiable;
    
    protected $table = 'acompras';
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
    
    protected $fillable = ['id', 'name', 'email', 'password'];
    protected $hidden = ['password', 'remember_token'];
}