<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{

    protected $fillable = [
        'nombre',
        'descripcion',
        'categoria_id',
        'precio',
        'iva_id',
        'destacado',
        'activo',
        'orden',
        'imagen',
    ];

    protected $casts = [
        'destacado' => 'boolean',
        'activo' => 'boolean',
        'precio' => 'decimal:2',
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function iva()
    {
        return $this->belongsTo(Iva::class);
    }

}
