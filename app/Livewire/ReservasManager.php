<?php

namespace App\Livewire;

use App\Models\Preferencia;
use App\Models\Reserva;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ReservasManager extends Component
{
    public $user_id;
    public $nombre;
    public $telefono;
    public $email;
    public $fecha;
    public $turno;
    public $personas;
    public $mesas;
    public $confirmada;
    public $mode;
    public $reserva_id;
    public $num_mesas;

    public $fecha_reserva;
    public $turno_reserva;
    
    public function mount()
    {
        $this->num_mesas = Preferencia::first()->mesas ?? 5;
        $this->mesas = [];
        $this->confirmada = false;
        $this->fecha_reserva = now()->format('Y-m-d');
        $this->turno = 1;
    }

    protected function rules()
    {
        return [
            'user_id' => 'required|exists:users,id',
            'nombre' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'email' => 'nullable|email',
            'fecha' => 'required|date',
            'turno' => 'required|in:1,2',
            'personas' => 'required|integer|min:1|max:30',
            'mesas' => 'required|array|min:1',
            'mesas.*' => 'integer|min:1|max:' . $this->num_mesas,
            'confirmada' => 'boolean',
        ];
    }

    public function create()
    {
        $this->mode = true;
        $this->user_id = auth()->user()->id;
        $this->fecha = $this->fecha_reserva;
        $this->modal('reservas-modal')->show();
    }

    public function edit($id)
    {
        $this->reserva_id = $id;
        $this->mode = false;
        $reserva = Reserva::findOrFail($this->reserva_id);
        $this->user_id = auth()->user()->id;
        $this->nombre = $reserva->nombre;
        $this->telefono = $reserva->telefono;
        $this->email = $reserva->email;
        $this->fecha = $reserva->fecha->format('Y-m-d');
        $this->turno = $reserva->turno;
        $this->personas = $reserva->personas;
        $this->mesas = $reserva->mesas ?? [];
        $this->confirmada = $reserva->confirmada;
        $this->modal('reservas-modal')->show();
    }

    public function save()
    {
        $this->mesas = array_map('intval', $this->mesas ?? []);
        $data = $this->validate();

        if ($this->mode) {
            Reserva::create($data);
            session()->flash('success', 'Reserva creada correctamente');
        } else {
            Reserva::findOrFail($this->reserva_id)->update($data);
            session()->flash('success', 'Reserva actualizada correctamente');
        }
        $this->modal('reservas-modal')->close();
        $this->resetReserva();
    }

    public function delete($id)
    {
        $this->reserva_id = $id;
        $this->modal('delete-modal')->show();
    }

    public function deleteConfirm()
    {
        Reserva::findOrFail($this->reserva_id)->delete();
        session()->flash('danger', 'Reserva eliminada correctamente');
        $this->modal('delete-modal')->close();
    }

    public function resetReserva()
    {
        $this->reset([
            'nombre',
            'telefono',
            'email',
            'fecha',
            'personas',
            'mesas',
            'confirmada',
            'reserva_id',
            'mode',
        ]);

        $this->mesas = [];
        $this->confirmada = false;
    }

    public function confirmar($id)
    {
        Reserva::findOrFail($id)->update(['confirmada' => true]);
        session()->flash('success', 'Reserva confirmada correctamente');
    }

    #[Computed]
    public function reservas()
    {
        return Reserva::orderBy('fecha', 'desc')
            ->orderBy('turno', 'asc')
            ->whereDate('fecha', $this->fecha_reserva)
            ->when($this->turno_reserva, function ($query) {
                return $query->where('turno', $this->turno_reserva);
            })
            ->get();
    }

    public function render()
    {
        return view('livewire.reservas-manager');
    }
}
