<?php

namespace App\Livewire;

use App\Models\Recibo;
use App\Models\LineasRecibo;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use App\Notifications\PedidoListoNotification;

class CocinasManager extends Component
{
    Use WithPagination;

    public $cocina;
    public $dia;
    public $reciboId;
    public $codigo;
    public $fecha;
    public $estado = 'pendiente';

    public function mount()
    {
        $this->dia = now()->format('Y-m-d');
    }

    public function show(Recibo $recibo)
    {
        $this->reciboId = $recibo->id;
        $recibo = Recibo::findOrFail($this->reciboId);
        $this->codigo = $recibo->codigo;
        $this->fecha = $recibo->created_at->format('H:i:s');
        if($recibo->cocina == 'pendiente') {
            $recibo->cocina = 'cocinando';
            $recibo->save();
        }
        $this->modal('ver-pedido')->show();
    }

    public function listo()
    {
        $recibo = Recibo::with('lineasRecibo')->findOrFail($this->reciboId);
        $recibo->cocina = 'listo';
        $recibo->save();

        LineasRecibo::where('recibo_id', $this->reciboId)
            ->whereHas('producto.categoria', function ($query) {
                $query->where('cocina', true);
            })
            ->update(['listo' => true]);
        
        if ($recibo->dato) {
            $datos = $recibo->dato;
            $datos->notify(new PedidoListoNotification($recibo));
            $datos->notificacion = now();
            $datos->save();
        }

        $this->modal('ver-pedido')->close();

        session()->flash('success', 'Pedido completado');
    }

    public function terminado($id)
    {
        $plato = LineasRecibo::findOrFail($id);
        $plato->listo = true;
        $plato->save();
    }

    #[Computed]
    public function recibos()
    {
        $query = Recibo::whereNotNull('cocina')
            ->whereDate('created_at', $this->dia)
            ->with('user')
            ->conPendientesCocina();
        if ($this->estado === 'pendiente') {
            $query->whereHas('lineasRecibo', function ($q) {
                $q->where('listo', false)
                ->whereHas('producto.categoria', function ($subq) {
                    $subq->where('cocina', true);
                });
            });
        } else {
            $query->where('cocina', $this->estado);
        }

        return $query->oldest()->paginate(15);
    }

    #[Computed]
    public function lineas()
    {
        return LineasRecibo::where('recibo_id', $this->reciboId)
            ->whereHas('producto.categoria', fn ($q) => $q->where('cocina', true))
            ->with('producto')
            ->get();
    }

    public function render()
    {
        return view('livewire.cocinas-manager');
    }
}
