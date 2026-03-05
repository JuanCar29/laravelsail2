<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'cocina',
    ];

    protected $casts = [
        'cocina' => 'boolean',
    ];

    public function productos()
    {
        return $this->hasMany(Producto::class);
    }
    
}
