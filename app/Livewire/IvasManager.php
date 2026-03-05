<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\Iva;

class IvasManager extends Component
{
    public $nombre;
    public $valor;
    public $defecto;
    public $ivaId = null;

    public function create()
    {
        $this->reset();
        $this->ivaId = null;
    }

    public function edit(Iva $iva)
    {
        $this->ivaId = $iva->id;
        $this->nombre = $iva->nombre;
        $this->valor = $iva->valor;
        $this->defecto = $iva->defecto;
    }

    public function save()
    {
        $this->validate([
            'nombre' => 'required|string|max:255',
            'valor' => 'required|numeric',
            'defecto' => 'required|boolean',
        ]);

        if ($this->ivaId) {
            $iva = Iva::findOrFail($this->ivaId);
            $iva->update([
                'nombre' => $this->nombre,
                'valor' => $this->valor,
                'defecto' => $this->defecto,
            ]);
            session()->flash('success', '¡IVA actualizado con éxito!');
        } else {
            Iva::create([
                'nombre' => $this->nombre,
                'valor' => $this->valor,
                'defecto' => $this->defecto,
            ]);
            session()->flash('success', '¡IVA creado con éxito!');
        }

        $this->reset();
    }

    public function cancel()
    {
        $this->reset();
    }

    #[Computed]
    public function ivas()
    {
        return Iva::orderBy('valor', 'asc')->get();
    }

    public function render()
    {
        return view('livewire.ivas-manager');
    }
}
