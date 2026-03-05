<?php

namespace App\Livewire;

use App\Models\Recibo;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class RecibosManager extends Component
{
    use WithPagination;

    #[Url]
    public $estado = '';
    public $mesa = 1;
    public $cocina = null;
    public $lugar = 'barra';
    public $dia;

    public function mount()
    {
        $this->dia = now()->format('Y-m-d');
    }

    public function create()
    {
        $recibo = Recibo::create([
            'user_id' => auth()->user()->id,
            'estado' => 'abierto',
            'mesa' => $this->mesa,
            'cocina' => $this->cocina,
            'lugar' => $this->lugar,
            ]);
            return redirect()->route('pos', $recibo->id);
    }

    public function show(Recibo $recibo)
    {
        $recibo = Recibo::findOrFail($recibo->id);
        $recibo->update([
            'estado' => 'abierto',
        ]);

        return redirect()->route('pos', $recibo->id);        
    }

    #[Computed]
    public function recibos()
    {
        return Recibo::when($this->estado, function ($query) {
                $query->where('estado', $this->estado);
            })
            ->where('user_id', auth()->user()->id)
            ->whereDate('created_at', $this->dia)
            ->with('user')
            ->conPendientesCocina()
            ->latest()
            ->paginate(10);
    }

    public function updatedDia($dia)
    {
        $this->resetPage();
    }

    #[Computed]
    public function pedidosCocina()
    {
        return Recibo::whereDate('created_at', $this->dia)
            ->whereIn('cocina', ['Cocinando', 'Pendiente'])
            ->count();
    }

}
