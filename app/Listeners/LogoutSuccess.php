<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use App\Models\Registro;

class LogoutSuccess
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Logout $event): void
    {
        $registro = Registro::where('user_id', $event->user->id)
            ->whereNull('salida')
            ->orderBy('entrada', 'desc')
            ->first();

        if ($registro) {
            $registro->update(['salida' => now()]);
        }
    }
}
