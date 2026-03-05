<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LineasRecibo extends Model
{
    protected $fillable = [
        'recibo_id',
        'producto_id',
        'nombre_producto',
        'cantidad',
        'precio_unitario',
        'subtotal',
        'iva',
        'listo',
    ];

    protected $casts = [
        'listo' => 'boolean',
        'iva' => 'decimal:2',
    ];

    public function recibo()
    {
        return $this->belongsTo(Recibo::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function getBaseImponibleAttribute()
    {
        $porcentaje = $this->attributes['iva'] ?? 0;
        if ($porcentaje <= 0) {
            return $this->subtotal;
        }

        return $this->subtotal / (1 + ($porcentaje / 100));
    }

    public function getImporteIvaAttribute()
    {
        return $this->subtotal - $this->base_imponible;
    }
    
}
