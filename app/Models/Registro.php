<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registro extends Model
{
    protected $fillable = [
        'user_id',
        'entrada',
        'salida',
    ];

    protected $casts = [
        'entrada' => 'datetime',
        'salida' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getHorasAttribute()
    {
        return number_format($this->entrada->diffInMinutes($this->salida) / 60, 2, ",", ".");
    }
}
