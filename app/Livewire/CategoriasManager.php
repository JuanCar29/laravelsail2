<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Categoria;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;

class CategoriasManager extends Component
{
    use WithPagination;

    public $nombre;
    public $descripcion;
    public $cocina = false;
    public $categoriaId = null;
    public $search = '';

    protected $rules = [
        'nombre' => 'required|string|max:255',
        'descripcion' => 'nullable|string',
        'cocina' => 'required|boolean',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->resetForm();
        $this->categoriaId = null;
    }

    public function edit(Categoria $categoria)
    {
        $this->categoriaId = $categoria->id;
        $this->nombre = $categoria->nombre;
        $this->descripcion = $categoria->descripcion;
        $this->cocina = $categoria->cocina;
    }

    public function save()
    {
        $this->validate([
            'nombre' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categorias', 'nombre')->ignore($this->categoriaId),
            ],
            'descripcion' => 'nullable|string',
        ]);

        if ($this->categoriaId) {
            $categoria = Categoria::findOrFail($this->categoriaId);
            $categoria->update([
                'nombre' => $this->nombre,
                'descripcion' => $this->descripcion,
                'cocina' => $this->cocina,
            ]);
            session()->flash('success', '¡Categoría actualizada con éxito!');
        } else {
            Categoria::create([
                'nombre' => $this->nombre,
                'descripcion' => $this->descripcion,
                'cocina' => $this->cocina,
            ]);
            session()->flash('success', '¡Categoría creada con éxito!');
        }

        $this->resetForm();
    }

    public function cancel()
    {
        $this->resetForm();
    }

    protected function resetForm()
    {
        $this->reset(['nombre', 'descripcion', 'categoriaId', 'cocina']);
        $this->resetValidation();
    }

    #[Computed]
    public function categorias()
    {
        return Categoria::where('nombre', 'like', "%{$this->search}%")
            ->orderBy('nombre')
            ->paginate(10);
    }

}