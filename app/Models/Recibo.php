<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Recibo extends Model
{
    protected $fillable = [
        'user_id',
        'codigo',
        'estado',
        'mesa',
        'cocina',
        'lugar',
    ];

    protected static function booted()
    {
        static::creating(function (Recibo $recibo) {
            // Solo generar código si no se ha definido manualmente
            if (empty($recibo->codigo)) {
                $preferencia = DB::transaction(function () {
                    $preferencia = Preferencia::lockForUpdate()->first();

                    if (!$preferencia) {
                        // Opcional: crear configuración por defecto
                        $preferencia = Preferencia::create([
                            'prefijo' => 'REC',
                            'siguiente' => 1,
                            // puedes agregar otros campos con valores por defecto
                        ]);
                    }

                    $codigo = $preferencia->prefijo . str_pad($preferencia->siguiente, 6, '0', STR_PAD_LEFT);

                    $preferencia->increment('siguiente');

                    return [$preferencia, $codigo];
                });

                $recibo->codigo = $preferencia[1]; // el código generado
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lineasRecibo()
    {
        return $this->hasMany(LineasRecibo::class);
    }

    public function getTotalAttribute()
    {
        return $this->lineasRecibo()->sum('subtotal');
    }

    public function getEstadoAttribute($value)
    {
        return ucfirst($value);
    }

    public function getEstadoColorAttribute()
    {
        return match ($this->estado) {
            'Abierto' => 'amber',
            'Efectivo'   => 'green',
            'Tarjeta'   => 'blue',
            'Pendiente' => 'red',
            default     => 'gray',
        };
    }

    public function getEstadoIconAttribute()
    {
        return match ($this->estado) {
            'Abierto' => 'lock-open',
            'Efectivo'   => 'currency-euro',
            'Tarjeta'   => 'credit-card',
            'Pendiente' => 'clock',
            default     => 'question-mark-circle',
        };
    }

    public function getLugarAttribute($value)
    {
        return $value ? ucfirst($value) : '-';
    }

    public function getCocinaAttribute($value)
    {
        return $value ? ucfirst($value) : 'No cocina';
    }

    public function getCocinaColorAttribute()
    {
        return match ($this->cocina) {
            'Pendiente' => 'amber',
            'Cocinando'   => 'blue',
            'Listo'   => 'green',
            default     => 'gray',
        };
    }

    public function dato()
    {
        return $this->hasOne(DatosRecibo::class);
    }

    public function scopeConPendientesCocina($query)
    {
        return $query->withCount([
            'lineasRecibo as pendientes_cocina' => function ($q) {
                $q->where(function ($subq) {
                    $subq->where('listo', false)->orWhereNull('listo');
                })
                ->whereHas('producto.categoria', function ($subq) {
                    $subq->where('cocina', true);
                });
            }
        ]);
    }
}
