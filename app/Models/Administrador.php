<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Administrador extends Authenticatable
{
    use HasFactory, Notifiable;
    
    protected $table = 'administradores';
    protected $primaryKey = 'id';
    public $incrementing = false;
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
    
    public function getAuthPassword()
    {
        return $this->pass;
    }
    
    public function getEmailForPasswordReset()
    {
        return $this->mail;
    }
    
    public function getEmailAttribute()
    {
        return $this->mail;
    }

    protected $hidden = ['pass', 'passtemp', 'remember_token'];
    protected $casts = ['principal' => 'integer'];
}