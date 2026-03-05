<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class DatosRecibo extends Model
{
    use Notifiable;

    protected $fillable = [
        'recibo_id',
        'telefono',
        'email',
        'notificacion',
    ];

    protected $casts = [
        'notificacion' => 'timestamp',
    ];

    public function recibo()
    {
        return $this->belongsTo(Recibo::class);
    }
}
