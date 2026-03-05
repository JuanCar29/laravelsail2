<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Recibo;
use App\Models\Producto;
use App\Models\Preferencia;
use App\Models\Categoria;
use App\Models\DatosRecibo;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;

class PosManager extends Component
{
    use WithPagination;

    public $recibo;
    public $cantidad = 1;
    public $producto_id;
    public $producto_nombre;
    public int $totalMesas;
    public $mesa;
    public $lugar;
    public ?int $categoriaSeleccionada = 0;
    public ?string $nombreCategoriaSeleccionada = 'Favoritos';
    public $nuevoPrecio;
    public $telefono;
    public $email;

    public function mount($recibo)
    {
        
        $this->recibo = Recibo::with('lineasRecibo', 'user', 'dato')->findOrFail($recibo);
        $this->authorize('pos', $this->recibo);
        $this->lugar = $this->recibo->lugar;
        $this->mesa = $this->recibo->mesa;

        $preferencia = Preferencia::first();
        $this->totalMesas = (int) $preferencia?->mesas ?? 1;
    }

    #[Computed]
    public function lineasRecibo()
    {
        return $this->recibo->lineasRecibo;
    }

    #[Computed]
    public function recibosPendientes()
    {
        return Recibo::where('estado', 'pendiente')
            ->where('user_id', auth()->user()->id)
            ->count();
    }

    #[Computed]
    public function productos()
    {
        return Producto::when($this->categoriaSeleccionada, function ($query) {
                $query->where('categoria_id', $this->categoriaSeleccionada);
            })
            ->when($this->categoriaSeleccionada === 0, function ($query) {
                $query->where('destacado', true);
            })
            ->orderBy('orden', 'asc')
            ->with('iva')
            ->paginate(24);
    }

    #[Computed]
    public function categorias()
    {
        return Categoria::Orderby('nombre', 'asc')
            ->get();
    }

    public function buscarCategoria(int $id)
    {
        $this->categoriaSeleccionada = $id;
        $categoria = $this->categorias->firstWhere('id', $id);
        $this->nombreCategoriaSeleccionada = $categoria ? $categoria->nombre : 'Favoritos';
    }

    public function render()
    {
        return view('livewire.pos-manager', [
            'recibo' => $this->recibo,
        ]);
    }

    public function agregarProductoConId($id)
    {
        $this->producto_id = $id;
        $this->agregarProducto();
    }

    public function agregarProducto()
    {
        if (!$this->producto_id) {
            session()->flash('danger', 'Selecciona un producto.');
            return;
        }

        $producto = Producto::find($this->producto_id);
        if (!$producto) {
            session()->flash('danger', 'Producto no encontrado.');
            return;
        }

        $this->recibo->load('lineasRecibo');

        $lineaExistente = $this->recibo->lineasRecibo->firstWhere('producto_id', $this->producto_id);

        if ($lineaExistente && ! $lineaExistente->listo) {
            $lineaExistente->cantidad += $this->cantidad;
            $lineaExistente->subtotal = $lineaExistente->cantidad * $lineaExistente->precio_unitario;
            $lineaExistente->save();
        } else {
            $this->recibo->lineasRecibo()->create([
                'producto_id' => $this->producto_id,
                'nombre_producto' => $producto->nombre,
                'cantidad' => $this->cantidad,
                'precio_unitario' => $producto->precio,
                'subtotal' => $producto->precio * $this->cantidad,
                'iva' => $producto->iva->valor ?? 0,
            ]);
        }

        $this->recibo->load('lineasRecibo');

        $this->reset('cantidad');
    }

    public function quitarProducto($lineaId)
    {
        $linea = $this->recibo->lineasRecibo->firstWhere('id', $lineaId);

        if ($linea) {
            $linea->delete();
            $this->recibo->load('lineasRecibo');
        }
    }

    public function quitarCantidad($lineaId)
    {
        $linea = $this->recibo->lineasRecibo->firstWhere('id', $lineaId);

        if ($linea && $linea->cantidad > 1) {
            $linea->cantidad -= 1;
            $linea->subtotal = $linea->cantidad * $linea->precio_unitario;
            $linea->save();
            $this->recibo->load('lineasRecibo');
        }
    }

    public function editarPrecio($lineaId)
    {
        $this->validate([
            'nuevoPrecio' => 'required|numeric|min:0',
        ]);

        $linea = $this->recibo->lineasRecibo->firstWhere('id', $lineaId);
        $linea->precio_unitario = $this->nuevoPrecio;
        $linea->subtotal = $linea->cantidad * $this->nuevoPrecio;
        $linea->save();

        $this->recibo->load('lineasRecibo');
        $this->modal('edit-precio-' . $lineaId)->close();
        $this->reset('nuevoPrecio');
    }

    public function precio($precio)
    {
        if ($precio === 'C') {
            $this->nuevoPrecio = '';
            return;
        }
        $this->nuevoPrecio .= $precio;
    }

    public function resetPrecio()
    {
        $this->reset('nuevoPrecio');
    }

    public function actualizarEstado($estado)
    {
        $this->recibo->mesa = $this->mesa ?? 1;
        $this->recibo->lugar = $this->lugar ?? 'barra';
        $this->recibo->estado = $estado;
        
        $tieneProductosDeCocina = $this->recibo->lineasRecibo()
            ->whereHas('producto.categoria', fn ($q) => $q->where('cocina', true))
            ->exists();

        if ( $tieneProductosDeCocina && $this->recibo->cocina === 'No cocina' ) {
            $this->recibo->cocina = 'pendiente';
        }
        
        $this->recibo->save();

        session()->flash('success', 'Estado actualizado correctamente.');
    }

    public function create()
    {
        $recibo = Recibo::create([
            'user_id' => auth()->user()->id,
            'estado' => 'abierto',
            'mesa' => 1,
            'cocina' => null,
            'lugar' => 'barra',
        ]);

        return redirect()->route('pos', $recibo->id);
    }

    public function abrirModal()
    {
        $datos = $this->recibo->dato;

        $this->telefono = $datos?->telefono ?? '';
        $this->email = $datos?->email ?? '';

        $this->modal('datos-recogida')->show();
    }

    public function guardarDatosRecogida()
    {
        $this->validate([
            'telefono' => 'required|numeric',
            'email' => 'nullable|email',
        ]);

        DatosRecibo::updateOrCreate(
            ['recibo_id' => $this->recibo->id],
            [
                'telefono' => $this->telefono,
                'email' => $this->email,
            ]
        );

        Recibo::where('id', $this->recibo->id)->update([
            'lugar' => 'Recoger',
            'estado' => 'Pendiente',
            'cocina' => 'Pendiente',
        ]);

        $this->redirect(route('pos', $this->recibo->id));

        session()->flash('success', 'Datos guardados correctamente.');

        $this->modal('datos-recogida')->close();
    }
}
