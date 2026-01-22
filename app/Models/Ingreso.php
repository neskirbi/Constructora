<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ingreso extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ingresos';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'codigo_obra',
        'nombre_obra',
        'descripcion',
        'cliente',
        'ubicacion',
        'presupuesto',
        'monto_adjudicado',
        'fecha_licitacion',
        'fecha_adjudicacion',
        'fecha_inicio',
        'fecha_fin_estimada',
        'fecha_fin_real',
        'estado',
        'categoria',
        'progreso',
        'observaciones',
        'responsable_id',
        'sucursal_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'presupuesto' => 'decimal:2',
        'monto_adjudicado' => 'decimal:2',
        'fecha_licitacion' => 'date',
        'fecha_adjudicacion' => 'date',
        'fecha_inicio' => 'date',
        'fecha_fin_estimada' => 'date',
        'fecha_fin_real' => 'date',
        'progreso' => 'integer',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'categoria' => '',
        'observaciones' => '',
        'progreso' => 0,
        'estado' => 'licitacion',
    ];

    /**
     * Scope para filtrar por estado.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $estado
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEstado($query, $estado)
    {
        if ($estado) {
            return $query->where('estado', $estado);
        }
        return $query;
    }

    /**
     * Scope para buscar por texto.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where(function($q) use ($search) {
                $q->where('nombre_obra', 'like', "%{$search}%")
                  ->orWhere('codigo_obra', 'like', "%{$search}%")
                  ->orWhere('cliente', 'like', "%{$search}%")
                  ->orWhere('ubicacion', 'like', "%{$search}%")
                  ->orWhere('descripcion', 'like', "%{$search}%");
            });
        }
        return $query;
    }

    /**
     * Scope para obras en licitación.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEnLicitacion($query)
    {
        return $query->where('estado', 'licitacion');
    }

    /**
     * Scope para obras adjudicadas.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAdjudicadas($query)
    {
        return $query->where('estado', 'adjudicada');
    }

    /**
     * Scope para obras en ejecución.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEnEjecucion($query)
    {
        return $query->where('estado', 'en_ejecucion');
    }

    /**
     * Scope para obras finalizadas.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFinalizadas($query)
    {
        return $query->where('estado', 'finalizada');
    }

    /**
     * Get the estado label with color.
     *
     * @return array
     */
    public function getEstadoLabelAttribute()
    {
        $estados = [
            'licitacion' => ['label' => 'En Licitación', 'color' => 'warning', 'icon' => 'fa-file-signature'],
            'adjudicada' => ['label' => 'Adjudicada', 'color' => 'info', 'icon' => 'fa-award'],
            'en_ejecucion' => ['label' => 'En Ejecución', 'color' => 'primary', 'icon' => 'fa-hammer'],
            'suspendida' => ['label' => 'Suspendida', 'color' => 'secondary', 'icon' => 'fa-pause'],
            'finalizada' => ['label' => 'Finalizada', 'color' => 'success', 'icon' => 'fa-check-circle'],
            'cancelada' => ['label' => 'Cancelada', 'color' => 'danger', 'icon' => 'fa-times-circle'],
        ];

        return $estados[$this->estado] ?? ['label' => ucfirst($this->estado), 'color' => 'secondary', 'icon' => 'fa-question'];
    }

    /**
     * Get the formatted presupuesto.
     *
     * @return string
     */
    public function getPresupuestoFormattedAttribute()
    {
        return '$' . number_format($this->presupuesto, 2);
    }

    /**
     * Get the formatted monto_adjudicado.
     *
     * @return string
     */
    public function getMontoAdjudicadoFormattedAttribute()
    {
        return '$' . number_format($this->monto_adjudicado, 2);
    }

    /**
     * Get the progress color based on percentage.
     *
     * @return string
     */
    public function getProgressColorAttribute()
    {
        if ($this->progreso >= 100) return 'success';
        if ($this->progreso >= 75) return 'primary';
        if ($this->progreso >= 50) return 'info';
        if ($this->progreso >= 25) return 'warning';
        return 'danger';
    }

    /**
     * Check if the obra is active.
     *
     * @return bool
     */
    public function getIsActiveAttribute()
    {
        return in_array($this->estado, ['adjudicada', 'en_ejecucion']);
    }

    /**
     * Check if the obra is completed.
     *
     * @return bool
     */
    public function getIsCompletedAttribute()
    {
        return $this->estado === 'finalizada';
    }

    /**
     * Get the duration in days.
     *
     * @return int|null
     */
    public function getDuracionDiasAttribute()
    {
        if ($this->fecha_inicio && $this->fecha_fin_real) {
            return $this->fecha_inicio->diffInDays($this->fecha_fin_real);
        }
        
        if ($this->fecha_inicio && $this->fecha_fin_estimada) {
            return $this->fecha_inicio->diffInDays($this->fecha_fin_estimada);
        }
        
        return null;
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = str_replace('-', '', \Illuminate\Support\Str::uuid());
            }
        });
    }
}