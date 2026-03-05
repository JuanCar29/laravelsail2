@component('mail::message')
## ¡Listo para recoger!

Su pedido #{{ $recibo->codigo }} ya está preparado.

@foreach($recibo->lineasRecibo as $linea)
• {{ $linea->nombre_producto }} x {{ $linea->cantidad }}
<br>
@endforeach

<br>
<strong>Total a pagar: {{ number_format($recibo->total, 2, ',', '.') }} €</strong>

<br>
Gracias por su confianza.
@endcomponent