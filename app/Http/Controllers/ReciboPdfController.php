<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Recibo;
use App\Models\Preferencia;

class ReciboPdfController extends Controller
{
    public function __invoke(Recibo $recibo)
    {
        $preferencias = Preferencia::first();

        $recibo->loadMissing('lineasRecibo');

        $baseTotal = $recibo->lineasRecibo->sum('base_imponible');
        $ivaTotal = $recibo->lineasRecibo->sum('importe_iva');
        $total = $recibo->lineasRecibo->sum('subtotal');

        $porcentajeIVA = $recibo->lineasRecibo->first()->iva;

        $pdf = Pdf::loadView('recibos.pdf', compact('recibo', 'preferencias', 'baseTotal', 'ivaTotal', 'total', 'porcentajeIVA'));
        return $pdf->stream("recibo_{$recibo->id}.pdf");
    }
}
