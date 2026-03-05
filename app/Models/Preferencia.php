<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Preferencia extends Model
{

    protected $fillable = [
        'nombre',
        'direccion',
        'telefono',
        'email',
        'logo',
        'cp',
        'ciudad',
        'provincia',
        'cif',
        'prefijo',
        'siguiente',
        'mesas',
    ];

}
