<?php

namespace App\Livewire\Traits;

use App\Models\Categoria;
use Livewire\Attributes\Computed;

trait HasCategorias
{
    #[Computed]
    public function categorias()
    {
        return Categoria::orderBy('nombre')->get();
    }
}