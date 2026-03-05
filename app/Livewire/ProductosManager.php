<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use Illuminate\Validation\Rule;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Iva;
use App\Livewire\Traits\HasCategorias;

class ProductosManager extends Component
{
    use WithPagination;
    use HasCategorias;

    public $nombre;
    public $descripcion;
    public $categoria_id;
    public $precio;
    public $iva_id;
    public $destacado = false;
    public $activo = true;
    public $orden;
    public $productoId = null;
    public $search = '';
    public $buscar_categoria = '';

    public function mount()
    {
        $iva = Iva::where('defecto', true)->first();
        $this->iva_id = $iva?->id;
    }

    protected $rules = [
        'nombre'        => 'required|string|max:255',
        'descripcion'   => 'nullable|string',
        'categoria_id'  => 'required|exists:categorias,id',
        'precio'        => 'required|numeric|min:0',
        'iva_id'           => 'required|exists:ivas,id',
        'destacado'     => 'boolean',
        'activo'        => 'boolean',
        'orden'         => 'nullable|integer|min:0',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->resetForm();
        $this->productoId = null;
    }

    public function edit(Producto $producto)
    {
        $this->productoId = $producto->id;
        $this->nombre = $producto->nombre;
        $this->descripcion = $producto->descripcion;
        $this->categoria_id = $producto->categoria_id;
        $this->precio = $producto->precio;
        $this->iva_id = $producto->iva_id;
        $this->destacado = (bool) $producto->destacado;
        $this->activo = (bool) $producto->activo;
        $this->orden = $producto->orden;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'nombre'        => $this->nombre,
            'descripcion'   => $this->descripcion,
            'categoria_id'  => $this->categoria_id,
            'precio'        => $this->precio,
            'iva_id'        => $this->iva_id,
            'destacado'     => $this->destacado,
            'activo'        => $this->activo,
            'orden'         => $this->orden ?? null,
        ];

        if ($this->productoId) {
            $producto = Producto::findOrFail($this->productoId);
            $producto->update($data);
            session()->flash('success', '¡Producto actualizado con éxito!');
        } else {
            Producto::create($data);
            session()->flash('success', '¡Producto creado con éxito!');
        }

        $this->resetForm();
    }

    public function cancel()
    {
        $this->resetForm();
    }

    protected function resetForm()
    {
        $this->reset([
            'nombre',
            'descripcion',
            'categoria_id',
            'precio',
            'destacado',
            'activo',
            'orden',
            'productoId'
        ]);
        $this->resetValidation();
    }

    #[Computed]
    public function productos()
    {
        return Producto::with('categoria', 'iva')
            ->where('nombre', 'like', "%{$this->search}%")
            ->when($this->buscar_categoria, function ($query) {
                $query->where('categoria_id', $this->buscar_categoria);
             })
            ->orderBy('orden')
            ->orderBy('nombre')
            ->paginate(10);
    }

    #[Computed]
    public function ivas()
    {
        return Iva::orderBy('valor', 'asc')->get();
    }

}