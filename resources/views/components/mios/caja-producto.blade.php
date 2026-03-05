@props(['producto'])

<div 
    {{ $attributes->merge(['class' => 'p-2 bg-gray-100 rounded-lg cursor-pointer hover:bg-gray-200']) }}
>
    <p class="font-medium">{{ $producto->nombre }}</p>
    <p class="text-sm text-gray-600 text-right">{{ number_format($producto->precio, 2, ',', '.') }} €</p>
</div>