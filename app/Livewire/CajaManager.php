<?php

namespace App\Livewire;

use App\Models\Recibo;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;

class CajaManager extends Component
{
    use WithPagination;
    public $dia_desde;
    public $dia_hasta;

    public function mount()
    {
        $this->dia_desde = now()->startOfMonth()->format('Y-m-d');
        $this->dia_hasta = now()->endOfMonth()->format('Y-m-d');
    }

    #[Computed]
    public function cajas()
    {
        return DB::table('recibos')
            ->select(
                DB::raw('DATE_FORMAT(recibos.created_at, "%d-%m-%Y") as dia'),
                DB::raw('COUNT(DISTINCT recibos.id) as num_recibos'),
                DB::raw('SUM(CASE WHEN recibos.estado = "abierto" THEN lineas.subtotal ELSE 0 END) as total_abierto'),
                DB::raw('SUM(CASE WHEN recibos.estado = "pendiente" THEN lineas.subtotal ELSE 0 END) as total_pendiente'),
                DB::raw('SUM(CASE WHEN recibos.estado = "efectivo" THEN lineas.subtotal ELSE 0 END) as total_efectivo'),
                DB::raw('SUM(CASE WHEN recibos.estado = "tarjeta" THEN lineas.subtotal ELSE 0 END) as total_tarjeta'),
                DB::raw('SUM(lineas.subtotal) as total')
            )
            ->join('lineas_recibos as lineas', 'recibos.id', '=', 'lineas.recibo_id')
            ->whereDate('recibos.created_at', '>=', $this->dia_desde)
            ->whereDate('recibos.created_at', '<=', $this->dia_hasta)
            ->groupBy(DB::raw('DATE_FORMAT(recibos.created_at, "%d-%m-%Y")'))
            ->orderBy('dia', 'desc')
            ->paginate(15);
    }

    #[Computed]
    public function cajasTotales()
    {
        $result = DB::table('recibos')
            ->select(
                DB::raw('COUNT(DISTINCT recibos.id) as total_recibos'),
                DB::raw('SUM(CASE WHEN recibos.estado = "abierto" THEN lineas.subtotal ELSE 0 END) as total_abierto'),
                DB::raw('SUM(CASE WHEN recibos.estado = "pendiente" THEN lineas.subtotal ELSE 0 END) as total_pendiente'),
                DB::raw('SUM(CASE WHEN recibos.estado = "efectivo" THEN lineas.subtotal ELSE 0 END) as total_efectivo'),
                DB::raw('SUM(CASE WHEN recibos.estado = "tarjeta" THEN lineas.subtotal ELSE 0 END) as total_tarjeta'),
                DB::raw('SUM(lineas.subtotal) as total_general')
            )
            ->join('lineas_recibos as lineas', 'recibos.id', '=', 'lineas.recibo_id')
            ->whereDate('recibos.created_at', '>=', $this->dia_desde)
            ->whereDate('recibos.created_at', '<=', $this->dia_hasta)
            ->first();

        return [
            'total_recibos' => $result->total_recibos ?? 0,
            'total_abierto' => $result->total_abierto ?? 0,
            'total_pendiente' => $result->total_pendiente ?? 0,
            'total_efectivo' => $result->total_efectivo ?? 0,
            'total_tarjeta' => $result->total_tarjeta ?? 0,
            'total_general' => $result->total_general ?? 0,
        ];
    }

    #[Computed]
    public function cajasCamareros()
    {
        return DB::table('recibos')
            ->select(
                DB::raw('COUNT(DISTINCT recibos.id) as num_recibos'),
                DB::raw('SUM(lineas.subtotal) as total'),
                'users.name'
            )
            ->join('lineas_recibos as lineas', 'recibos.id', '=', 'lineas.recibo_id')   
            ->join('users', 'recibos.user_id', '=', 'users.id')
            ->whereDate('recibos.created_at', '>=', $this->dia_desde)
            ->whereDate('recibos.created_at', '<=', $this->dia_hasta)
            ->groupBy('users.name')
            ->get();
    }

    #[Computed]
    public function datosGrafico()
    {
        return DB::table('recibos')
            ->select(
                DB::raw('DATE_FORMAT(recibos.created_at, "%d-%m") as dia'), // Formato corto para el eje X
                DB::raw('SUM(lineas.subtotal) as total')
            )
            ->join('lineas_recibos as lineas', 'recibos.id', '=', 'lineas.recibo_id')
            ->whereDate('recibos.created_at', '>=', $this->dia_desde)
            ->whereDate('recibos.created_at', '<=', $this->dia_hasta)
            ->groupBy(DB::raw('DATE_FORMAT(recibos.created_at, "%d-%m")'), 'dia')
            ->orderBy('dia', 'asc')
            ->get();
    }

    public function render()
    {
        return view('livewire.caja-manager');
    }
}
