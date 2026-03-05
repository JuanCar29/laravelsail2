<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use App\Models\LineasRecibo;
use Illuminate\Support\Facades\DB;
use App\Livewire\Traits\HasCategorias;

class VentasManager extends Component
{
    use WithPagination;
    use HasCategorias;

    public $dia_desde;
    public $dia_hasta;
    public $categoriaSeleccionada;

    public function mount()
    {
        $this->dia_desde = now()->format('Y-m-d');
        $this->dia_hasta = now()->format('Y-m-d');
    }

    #[Computed]
    public function ventas()
    {
        return LineasRecibo::select(
            'producto_id',
            DB::raw('SUM(cantidad) as total_cantidad'),
            DB::raw('SUM(subtotal) as total_subtotal'),
            DB::raw('AVG(precio_unitario) as avg_precio_unitario'),
            DB::raw('COUNT(*) as total_lineas')
        )
        ->with('producto')
        ->whereDate('created_at', '>=', $this->dia_desde)
        ->whereDate('created_at', '<=', $this->dia_hasta)
        ->when($this->categoriaSeleccionada, function ($query) {
            $query->whereHas('producto', function ($q) {
                $q->where('categoria_id', $this->categoriaSeleccionada);
            });
        })
        ->groupBy('producto_id')
        ->orderBy('total_cantidad', 'desc')
        ->paginate(15);
    }

    public function render()
    {
        return view('livewire.ventas-manager');
    }
}
