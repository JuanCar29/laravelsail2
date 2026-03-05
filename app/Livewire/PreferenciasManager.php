<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Preferencia;
use Livewire\WithFileUploads;

class PreferenciasManager extends Component
{
    use WithFileUploads;

    public $nombre;
    public $direccion;
    public $telefono;
    public $email;
    public $logo;
    public $cp;
    public $ciudad;
    public $provincia;
    public $cif;
    public $prefijo;
    public $siguiente = 1;
    public $mesas = 1;

    public function mount()
    {
    $preferencia = Preferencia::firstOrCreate(
        [],
        [
            'nombre'      => '',
            'direccion'   => '',
            'telefono'    => '',
            'email'       => '',
            'logo'        => '',
            'cp'          => '',
            'ciudad'      => '',
            'provincia'   => '',
            'cif'         => '',
            'prefijo'     => 'REC',
            'siguiente'   => 1,
            'mesas'       => 1,
        ]
        );

        $this->fill($preferencia->only([
            'nombre', 'direccion', 'telefono', 'email', 'logo', 'cp',
            'ciudad', 'provincia', 'cif', 'prefijo', 'siguiente', 'mesas'
        ]));
    }

    public function savePreferencias()
    {
        $this->validate([
            'nombre' => 'required|string|max:255',
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'logo' => 'nullable|sometimes',
            'cp' => 'nullable|string|max:10',
            'ciudad' => 'nullable|string|max:100',
            'provincia' => 'nullable|string|max:100',
            'cif' => 'required|string|max:20',
            'prefijo' => 'required|string|max:10',
            'siguiente' => 'required|integer',
            'mesas' => 'required|integer|min:1',
        ]);

        $preferencia = Preferencia::first();

        $preferencia->fill($this->except(['logo']));

        if ($this->logo && !is_string($this->logo)) {
            $this->validate(['logo' => 'image|max:1024|mimes:jpeg,png,jpg']);
            $preferencia->logo = $this->logo->store('logos', 'public');
        }

        $preferencia->save();
        $this->logo = $preferencia->logo;

        session()->flash('success', '¡Datos guardados correctamente!');
    }

    public function render()
    {
        return view('livewire.preferencias-manager');
    }
}
