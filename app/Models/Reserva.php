<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    protected $fillable = [
        'user_id',
        'nombre',
        'telefono',
        'email',
        'fecha',
        'turno',
        'personas',
        'mesas',
        'confirmada',
    ];

    protected $casts = [
        'fecha' => 'date',
        'mesas' => 'array',
        'confirmada' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
