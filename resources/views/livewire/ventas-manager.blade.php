<x-mios.caja-principal>

    <x-mios.caja-titulo title="Ventas" />

    <x-mios.caja-filtros>
        <flux:input wire:model.live="dia_desde" type="date" label="Desde"/>
        <flux:input wire:model.live="dia_hasta" type="date" label="Hasta"/>
        <flux:select wire:model.live="categoriaSeleccionada" label="Categoría">
            <flux:select.option value="">Todas</flux:select.option>
            @foreach ($this->categorias as $categoria)
                <flux:select.option value="{{ $categoria->id }}">{{ $categoria->nombre }}</flux:select.option>
            @endforeach
        </flux:select>
    </x-mios.caja-filtros>

    <x-mios.data-table :headers="['Producto', 'Nº ventas', 'Precio medio', 'Total']">
        @forelse ($this->ventas as $venta)
            <tr>
                <td class="p-2">{{ $venta->producto->nombre }}</td>
                <td class="p-2">{{ $venta->total_cantidad }}</td>
                <td class="p-2">{{ number_format($venta->avg_precio_unitario, 2, ',', '.') }} €</td>
                <td class="p-2">{{ number_format($venta->total_subtotal, 2, ',', '.') }} €</td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="p-2 text-center">No hay ventas</td>
            </tr>
        @endforelse
    </x-mios.data-table>

    <div class="mt-4">
        {{ $this->ventas->links() }}
    </div>

</x-mios.caja-principal>
