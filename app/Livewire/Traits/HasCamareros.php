<?php

namespace App\Livewire\Traits;

use App\Models\User;
use Livewire\Attributes\Computed;

trait HasCamareros
{
    #[Computed]
    public function camareros()
    {
        return User::orderBy('name')->get();
    }
}