<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Recibo</title>
    <style>
        body {
            width: 280px;
            font-family: "Courier New", Courier, monospace;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }
        .divider { border-bottom: 1px dashed #000; margin: 5px 0; }
        .mb-1 { margin-bottom: 4px; }
        .mt-1 { margin-top: 4px; }
        .small { font-size: 10px; }
        .xl { font-size: 16px; }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td, th { padding: 2px 0; }
        .no-margin { margin: 0; }
    </style>
</head>
<body>
    <div class="text-center bold mb-1">
        {{ $preferencias->nombre }}
    </div>
    <div class="text-center small mb-1">
        {{ $preferencias->direccion }} · Tel: {{ $preferencias->telefono }}
    </div>

    <div class="text-center small mb-1">
        CIF: {{ $preferencias->cif }}
    </div>

    <div class="text-center small mb-1">
        Camarero: {{ $recibo->user->name }}
    </div>

    <div class="divider"></div>

    <div class="mb-1">
        <strong>#{{ $recibo->codigo }}</strong> | {{ $recibo->created_at->format('d/m/Y H:i') }}
    </div>

    <div class="mb-1">
        Mesa: {{ $recibo->mesa }} | Lugar: {{ $recibo->lugar }}
    </div>

    <div class="divider"></div>

    <table>
        <thead>
            <tr>
                <th class="text-left">Concepto</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($recibo->lineasRecibo as $linea)
            <tr>
                <td>
                    {{ $linea->cantidad }}x {{ $linea->nombre_producto }}
                </td>
                <td class="text-right">{{ number_format($linea->subtotal, 2, ',', '.') }}€</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="divider"></div>

    <table>
        <tr>
            <td><strong>Base imponible:</strong></td>
            <td class="text-right">{{ number_format($baseTotal, 2, ',', '.') }}€</td>
        </tr>
        <tr>
            <td><strong>IVA {{ $porcentajeIVA }}%:</strong></td>
            <td class="text-right">{{ number_format($ivaTotal, 2, ',', '.') }}€</td>
        </tr>
        <tr class="xl">
            <td><strong>Total:</strong></td>
            <td class="text-right bold">{{ number_format($total, 2, ',', '.') }}€</td>
        </tr>
    </table>

    <div class="text-center mt-1 small">
        @if($recibo->estado === 'Efectivo')
            Pago en efectivo
        @elseif($recibo->estado === 'Tarjeta')
            Pago con tarjeta
        @else
            Pago pendiente
        @endif
    </div>

    <div class="text-center small mt-1">
        ¡Gracias por su visita!
    </div>
</body>
</html>