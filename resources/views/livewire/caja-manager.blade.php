<x-mios.caja-principal>

    <x-mios.caja-titulo title="Caja" />

    <x-mios.caja-filtros>
        <flux:input label="Desde" wire:model.live="dia_desde" type="date" />
        <flux:input label="Hasta" wire:model.live="dia_hasta" type="date" />
    </x-mios.caja-filtros>

    <x-mios.data-table :headers="['Fecha', 'Nº recibos', 'Total abierto', 'Total pendiente', 'Total efectivo', 'Total tarjeta', 'Total']">
        @forelse ($this->cajas as $caja)
            <tr>
                <td class="p-2">{{ $caja->dia }}</td>
                <td class="p-2">{{ $caja->num_recibos ?? 0 }}</td>
                <td class="p-2">{{ number_format($caja->total_abierto, 2, ',', '.') }} €</td>
                <td class="p-2">{{ number_format($caja->total_pendiente, 2, ',', '.') }} €</td>
                <td class="p-2">{{ number_format($caja->total_efectivo, 2, ',', '.') }} €</td>
                <td class="p-2">{{ number_format($caja->total_tarjeta, 2, ',', '.') }} €</td>
                <td class="p-2">{{ number_format($caja->total, 2, ',', '.') }} €</td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="p-2">No hay cajas</td>
            </tr>
        @endforelse
        <tr class="bg-gray-100">
            <td colspan="2" class="p-4 font-semibold">Totales</td>
            <td class="p-4 font-semibold">{{ number_format($this->cajasTotales['total_abierto'], 2, ',', '.') }} €</td>
            <td class="p-4 font-semibold">{{ number_format($this->cajasTotales['total_pendiente'], 2, ',', '.') }} €</td>
            <td class="p-4 font-semibold">{{ number_format($this->cajasTotales['total_efectivo'], 2, ',', '.') }} €</td>
            <td class="p-4 font-semibold">{{ number_format($this->cajasTotales['total_tarjeta'], 2, ',', '.') }} €</td>
            <td class="p-4 font-semibold">{{ number_format($this->cajasTotales['total_general'], 2, ',', '.') }} €</td>
        </tr>   
    </x-mios.data-table>

    <div class="mt-4">
        {{ $this->cajas->links() }}
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 mt-4">
        @foreach ($this->cajasCamareros as $cajaCamarero)
            <div class="bg-white rounded-lg shadow-md p-4">
                <h3 class="text-lg font-semibold">{{ $cajaCamarero->name }}</h3>
                <p class="text-sm text-gray-600">{{ $cajaCamarero->num_recibos }}</p>
                <p class="text-sm text-gray-600">{{ number_format($cajaCamarero->total, 2, ',', '.') }} €</p>
            </div>
        @endforeach
    </div>

    <div wire:ignore class="mt-6" style="height: 300px;">
        <canvas id="ventasChart"></canvas>
    </div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    let ventasChart = null;
    
    function actualizarGrafico() {
        const canvas = document.getElementById('ventasChart').getContext('2d');
        if (!canvas) return;
    
        const datos = @json($this->datosGrafico);
        if (!datos || datos.length === 0) return;
        
        if (ventasChart instanceof Chart) {
            ventasChart.destroy();
        }
        
        ventasChart = new Chart(canvas, {
            type: 'bar',
            data: {
                labels: datos.map(d => d.dia),
                datasets: [{
                    label: 'Ventas',
                    data: datos.map(d => parseFloat(d.total)),
                    backgroundColor: 'rgba(75,192,192,0.2)',
                    borderColor: 'rgba(75,192,192,1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
    
    document.addEventListener('DOMContentLoaded', actualizarGrafico);
    
    document.addEventListener('livewire:init', () => {
        Livewire.hook('commit', ({ component, succeed }) => {
            succeed(() => {
                if (component.name === 'caja-manager') {
                    setTimeout(actualizarGrafico, 1);
                }
            });
        });
    });
</script>


</x-mios.caja-principal>
