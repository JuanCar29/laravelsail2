<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\Registro;
use App\Models\User;
use Livewire\WithPagination;
use App\Livewire\Traits\HasCamareros;

class RegistrosManager extends Component
{
    use WithPagination;
    use HasCamareros;

    public $user_id;
    public $entrada;
    public $salida;
    public $registroId = null;

    public $buscar_user;
    public $buscar_desde;
    public $buscar_hasta;

    public function boot()
    {
        if (empty($this->buscar_desde)) {
            $this->buscar_desde = now()->subDay(6)->format('Y-m-d');
        }
        if (empty($this->buscar_hasta)) {
            $this->buscar_hasta = now()->format('Y-m-d');
        }
    }

    public function create()
    {
        $this->reset();
        $this->registroId = null;
    }

    public function edit(Registro $registro)
    {
        $this->registroId = $registro->id;
        $this->user_id = $registro->user_id;
        $this->entrada = $registro->entrada->format('Y-m-d H:i');   
        $this->salida = $registro->salida ? $registro->salida->format('Y-m-d H:i') : null;
    }

    public function save()
    {
        $this->validate([
            'user_id' => 'required|numeric',
            'entrada' => 'required|date:Y-m-d H:i',
            'salida' => 'nullable|date:Y-m-d H:i',
        ]);

        if ($this->registroId) {
            $registro = Registro::findOrFail($this->registroId);
            $registro->update([
                'user_id' => $this->user_id,
                'entrada' => $this->entrada,
                'salida' => $this->salida,
            ]);
            session()->flash('success', '¡Registro actualizado con éxito!');
        } else {
            Registro::create([
                'user_id' => $this->user_id,
                'entrada' => $this->entrada,
                'salida' => $this->salida,
            ]);
            session()->flash('success', '¡Registro creado con éxito!');
        }

        $this->reset(['user_id', 'entrada', 'salida', 'registroId']);

    }

    public function delete(Registro $registro)
    {
        $registro->delete();
        session()->flash('danger', '¡Registro eliminado con éxito!');
    }

    public function cancel()
    {
        $this->reset(['user_id', 'entrada', 'salida', 'registroId']);
    }

    #[Computed]
    public function registros()
    {
        return Registro::orderBy('entrada', 'desc')
            ->whereDate('entrada', '>=', $this->buscar_desde)
            ->whereDate('entrada', '<=', $this->buscar_hasta)
            ->when($this->buscar_user, function ($query) {
                return $query->where('user_id', $this->buscar_user);
            })
            ->with('user')
            ->paginate(10);
    }

    #[Computed]
    public function horasTrabajadas()
    {
        return Registro::query()
            ->select('user_id')
            ->selectRaw('SUM(TIMESTAMPDIFF(MINUTE, entrada, salida)) as total_minutos, COUNT(*) as total_registros')
            ->whereDate('entrada', '>=', $this->buscar_desde)
            ->whereDate('entrada', '<=', $this->buscar_hasta)
            ->groupBy('user_id')
            ->with('user:id,name')
            ->get();
    }

    public function render()
    {
        return view('livewire.registros-manager');
    }
}
