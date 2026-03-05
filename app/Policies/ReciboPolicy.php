<?php

namespace App\Policies;

use App\Models\Recibo;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Date;

class ReciboPolicy
{

    public function pos(User $user, Recibo $recibo): bool
    {
        if ($user->nivel === 1) {
            return true;
        }

        if ($user->id !== $recibo->user_id) {
            return false;
        }

        return $recibo->created_at->isToday();
    }
}
