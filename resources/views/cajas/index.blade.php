<x-layouts.app title="Caja - Control de Ventas">
    <x-mios.caja-principal>
        <x-mios.caja-titulo title="Caja - Control de Ventas" />
    
        <form method="GET">
            <x-mios.caja-filtros>
                <flux:input name="fecha_desde" label="Desde" type="date" value="{{ $fecha_desde }}"/>
                <flux:input name="fecha_hasta" label="Hasta" type="date" value="{{ $fecha_hasta }}"/>
                <div class="flex justify-center gap-4">
                    <flux:button type="submit" variant="primary" class="cursor-pointer" size="sm">Aplicar Filtros</flux:button>
                    <flux:button href="{{ route('cajas') }}" class="cursor-pointer" size="sm">Limpiar</flux:button>
                </div>
            </x-mios.caja-filtros>
        </form>
    
    <!-- Resumen de Totales -->
    <div class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-5 gap-4 mb-6">
        <x-mios.card-totales titulo="Total General" valor="{{ $cajasTotales['total_general'] }}" color="gray"/>
        <x-mios.card-totales titulo="En Efectivo" valor="{{ $cajasTotales['total_efectivo'] }}" color="green"/>
        <x-mios.card-totales titulo="Con Tarjeta" valor="{{ $cajasTotales['total_tarjeta'] }}" color="blue"/>
        <x-mios.card-totales titulo="Pendientes" valor="{{ $cajasTotales['total_pendiente'] }}" color="yellow"/>
        <x-mios.card-totales titulo="Abiertos" valor="{{ $cajasTotales['total_abierto'] }}" color="red"/>
    </div>
    
    <!-- Gráfico -->
    <div class="bg-white rounded-xl shadow p-6 mb-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-700">Evolución de Ventas</h2>
            <span class="text-sm text-gray-500">
                {{ Carbon\Carbon::parse($fecha_desde)->format('d-m-Y') }} - 
                {{ Carbon\Carbon::parse($fecha_hasta)->format('d-m-Y') }}
            </span>
        </div>
        <div style="height: 300px;">
            <canvas id="ventasChart"></canvas>
        </div>
    </div>
    
    <!-- Tabla de Cajas por Día -->
     <x-mios.data-table :headers="['Fecha', 'Nº Recibos', 'Abierto', 'Pendiente', 'Efectivo', 'Tarjeta', 'Total Día']">
        @forelse ($cajas as $caja)
            <tr>
                <td class="p-2 whitespace-nowrap">
                    <div class="font-medium text-gray-900">
                        {{ \Carbon\Carbon::parse($caja->dia)->format('d-m-Y') }}
                    </div>
                    <div class="text-sm text-gray-500">
                        {{ \Carbon\Carbon::parse($caja->dia)->translatedFormat('l') }}
                    </div>
                </td>
                <td class="p-2">{{ $caja->num_recibos }}</td>
                <td class="p-2">{{ number_format($caja->total_abierto, 2, ',', '.') }} €</td>
                <td class="p-2">{{ number_format($caja->total_pendiente, 2, ',', '.') }} €</td>
                <td class="p-2">{{ number_format($caja->total_efectivo, 2, ',', '.') }} €</td>
                <td class="p-2">{{ number_format($caja->total_tarjeta, 2, ',', '.') }} €</td>
                <td class="p-2">{{ number_format($caja->total, 2, ',', '.') }} €</td>
            </tr>
        @empty
            <tr>
                <td colspan="7">No hay datos</td>
            </tr>
        @endforelse
        <tr class="bg-gray-50">
            <td colspan="2" class="p-2 font-semibold text-gray-900">TOTALES PERÍODO</td>
            <td class="p-2 font-semibold text-red-600">{{ number_format($cajasTotales['total_abierto'], 2, ',', '.') }} €</td>
            <td class="p-2 font-semibold text-yellow-600">{{ number_format($cajasTotales['total_pendiente'], 2, ',', '.') }} €</td>
            <td class="p-2 font-semibold text-green-600">{{ number_format($cajasTotales['total_efectivo'], 2, ',', '.') }} €</td>
            <td class="p-2 font-semibold text-blue-600">{{ number_format($cajasTotales['total_tarjeta'], 2, ',', '.') }} €</td>
            <td class="p-2 font-semibold text-gray-900">{{ number_format($cajasTotales['total_general'], 2, ',', '.') }} €</td>
        </tr>
    </x-mios.data-table>
    
    <!-- Cajas por Camarero -->
    <div class="bg-white rounded-xl shadow p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-700 mb-4">Ventas por Camarero</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($cajasCamareros as $index => $camarero)
                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center mb-3">
                        <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <span class="text-blue-600 font-bold">{{ substr($camarero->name, 0, 1) }}</span>
                        </div>
                        <div class="ml-3">
                            <h3 class="font-medium text-gray-900">{{ $camarero->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $camarero->num_recibos }} recibos</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold text-gray-900">
                            {{ number_format($camarero->total, 2, ',', '.') }} €
                        </div>
                        <div class="text-sm text-gray-500">
                            {{ number_format($camarero->total / max($cajasTotales['total_general'], 1) * 100, 1) }}% del total
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-mios.caja-principal>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Datos del gráfico desde PHP
        const datosGrafico = @json($datosGrafico);
        
        if (datosGrafico.length > 0) {
            const ctx = document.getElementById('ventasChart').getContext('2d');
            
            const chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: datosGrafico.map(d => d.dia),
                    datasets: [{
                        label: 'Efectivo',
                        data: datosGrafico.map(d => d.total_efectivo),
                        backgroundColor: 'rgba(59, 130, 246, 0.5)',
                        borderColor: 'rgb(59, 130, 246)',
                        borderWidth: 1,
                        borderRadius: 4,
                        hoverBackgroundColor: 'rgba(59, 130, 246, 0.7)',
                    },{
                        label: 'Tarjeta',
                        data: datosGrafico.map(d => d.total_tarjeta),
                        backgroundColor: 'rgba(239, 68, 68, 0.5)',
                        borderColor: 'rgb(239, 68, 68)',
                        borderWidth: 1,
                        borderRadius: 4,
                        hoverBackgroundColor: 'rgba(239, 68, 68, 0.7)',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                drawBorder: false,
                            },
                            ticks: {
                                callback: function(value) {
                                    return value.toFixed(0) + ' €';
                                },
                                font: {
                                    size: 11
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 11
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + context.parsed.y.toFixed(2).toString().replace('.', ',') + ' €';
                                }
                            }
                        }
                    }
                }
            });
        } else {
            // Mostrar mensaje si no hay datos
            document.getElementById('ventasChart').parentElement.innerHTML = `
                <div class="flex flex-col items-center justify-center h-full text-gray-500">
                    <p>No hay datos para mostrar en el gráfico</p>
                </div>
            `;
        }
    });
</script>
</x-layouts.app>