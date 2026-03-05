<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recibo;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CajaController extends Controller
{
    public function index(Request $request)
    {
        // Fechas por defecto: últimos 20 días
        $fecha_desde = $request->input('fecha_desde', Carbon::now()->subDays(20)->format('Y-m-d'));
        $fecha_hasta = $request->input('fecha_hasta', Carbon::now()->format('Y-m-d'));
        
        $fecha_desde_carbon = Carbon::parse($fecha_desde)->startOfDay();
        $fecha_hasta_carbon = Carbon::parse($fecha_hasta)->endOfDay();
        
        // Consulta usando JOIN con lineas_recibos
        $cajas = DB::table('recibos')
            ->join('lineas_recibos', 'recibos.id', '=', 'lineas_recibos.recibo_id')
            ->select(
                DB::raw('DATE(recibos.created_at) as dia'),
                DB::raw('COUNT(DISTINCT recibos.id) as num_recibos'),
                DB::raw('SUM(CASE WHEN recibos.estado = "abierto" THEN lineas_recibos.subtotal ELSE 0 END) as total_abierto'),
                DB::raw('SUM(CASE WHEN recibos.estado = "pendiente" THEN lineas_recibos.subtotal ELSE 0 END) as total_pendiente'),
                DB::raw('SUM(CASE WHEN recibos.estado = "efectivo" THEN lineas_recibos.subtotal ELSE 0 END) as total_efectivo'),
                DB::raw('SUM(CASE WHEN recibos.estado = "tarjeta" THEN lineas_recibos.subtotal ELSE 0 END) as total_tarjeta'),
                DB::raw('SUM(lineas_recibos.subtotal) as total')
            )
            ->whereBetween('recibos.created_at', [$fecha_desde_carbon, $fecha_hasta_carbon])
            ->groupBy(DB::raw('DATE(recibos.created_at)'))
            ->orderBy('dia', 'desc')
            ->get();
        
        // Calcular totales
        $cajasTotales = [
            'total_abierto' => $cajas->sum('total_abierto'),
            'total_pendiente' => $cajas->sum('total_pendiente'),
            'total_efectivo' => $cajas->sum('total_efectivo'),
            'total_tarjeta' => $cajas->sum('total_tarjeta'),
            'total_general' => $cajas->sum('total'),
        ];
        
        // Datos para el gráfico
        $datosGrafico = DB::table('recibos')
            ->join('lineas_recibos', 'recibos.id', '=', 'lineas_recibos.recibo_id')
            ->select(
                DB::raw('DATE(recibos.created_at) as dia'),
                DB::raw('SUM(CASE WHEN recibos.estado = "tarjeta" THEN lineas_recibos.subtotal ELSE 0 END) as total_tarjeta'),
                DB::raw('SUM(CASE WHEN recibos.estado = "efectivo" THEN lineas_recibos.subtotal ELSE 0 END) as total_efectivo')
            )
            ->whereBetween('recibos.created_at', [$fecha_desde_carbon, $fecha_hasta_carbon])
            ->groupBy(DB::raw('DATE(recibos.created_at)'))
            ->orderBy('dia', 'asc')
            ->get()
            ->map(function($item) {
                return [
                    'dia' => Carbon::parse($item->dia)->format('d-m'),
                    'total_tarjeta' => (float) $item->total_tarjeta,
                    'total_efectivo' => (float) $item->total_efectivo
                ];
            });
        
        // Datos por camarero
        $cajasCamareros = DB::table('recibos')
            ->join('users', 'recibos.user_id', '=', 'users.id')
            ->join('lineas_recibos', 'recibos.id', '=', 'lineas_recibos.recibo_id')
            ->select(
                'users.id',
                'users.name',
                DB::raw('COUNT(DISTINCT recibos.id) as num_recibos'),
                DB::raw('SUM(lineas_recibos.subtotal) as total')
            )
            ->whereBetween('recibos.created_at', [$fecha_desde_carbon, $fecha_hasta_carbon])
            ->groupBy('users.id', 'users.name')
            ->orderBy('total', 'desc')
            ->get();
        
        return view('cajas.index', compact(
            'cajas',
            'cajasTotales',
            'datosGrafico',
            'cajasCamareros',
            'fecha_desde',
            'fecha_hasta'
        ));
    }
}