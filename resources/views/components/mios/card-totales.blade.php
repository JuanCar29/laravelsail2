@props([
    'titulo' => '',
    'valor' => 0,
    'color' => 'gray', // gray, green, blue, yellow, red
])

@php
    // Mapear colores a clases de Tailwind
    $colores = [
        'gray' => 'text-gray-800',
        'green' => 'text-green-600',
        'blue' => 'text-blue-600',
        'yellow' => 'text-yellow-600',
        'red' => 'text-red-600',
    ];
    
    $colorClase = $colores[$color] ?? $colores['gray'];
@endphp

<div {{ $attributes->merge(['class' => 'bg-white rounded-xl shadow p-5 hover:shadow-lg transition-shadow duration-200']) }}>
    <div class="text-sm text-gray-500 mb-1">{{ $titulo }}</div>
    
    <div class="text-2xl font-bold {{ $colorClase }}">
        {{ number_format($valor, 2, ',', '.') }} €
    </div>
</div>