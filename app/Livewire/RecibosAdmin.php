<?php

namespace App\Livewire;

use App\Models\Recibo;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use App\Livewire\Traits\HasCamareros;

class RecibosAdmin extends Component
{
    use WithPagination;
    use HasCamareros;

    public $recibo;
    public $estado = '';
    public $mesa = 1;
    public $cocina = null;
    public $lugar = 'barra';
    public $dia;
    public $camarero;
    public $codigo;
    public $user_id;
    public $reciboId;

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

    public function edit(Recibo $recibo)
    {
        $this->reset(['codigo', 'user_id']);
        $this->reciboId = $recibo->id;
        $recibo = Recibo::findOrFail($this->reciboId);
        $this->codigo = $recibo->codigo;
        $this->user_id = $recibo->user_id;
        $this->modal('edit-recibo')->show();       
    }

    public function save()
    {
        $recibo = Recibo::findOrFail($this->reciboId);
        $recibo->update([
            'codigo' => $this->codigo,
            'user_id' => $this->user_id,
        ]);
        $this->modal('edit-recibo')->close();
        session()->flash('success', 'Recibo actualizado correctamente');
    }


    #[Computed]
    public function recibos()
    {
        return Recibo::when($this->estado, function ($query) {
                $query->where('estado', $this->estado);
            })
            ->when($this->camarero, function ($query) {
                $query->where('user_id', $this->camarero);
            })
            ->whereDate('created_at', $this->dia)
            ->with('user')
            ->conPendientesCocina()
            ->latest()
            ->paginate(10);
    }

    public function updatedDia($value)
    {
        $this->resetPage();
    }

    public function updatedEstado($value)
    {
        $this->resetPage();
    }

    public function updatedCamarero($value)
    {
        $this->resetPage();
    }

}
