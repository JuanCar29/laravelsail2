<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Iva extends Model
{
    protected $fillable = ['nombre', 'valor', 'defecto'];

    protected $casts = [
        'valor' => 'decimal:2',
        'defecto' => 'boolean',
    ];

    public function productos()
    {
        return $this->hasMany(Producto::class);
    }
    
}
