<x-mios.caja-principal>

    <x-mios.alerta />

    <x-mios.caja-titulo title="Recibos">
        <x-mios.create title="Nuevo Recibo" />
    </x-mios.caja-titulo>

    <div wire:poll.3s>
        <x-mios.caja-filtros>
            <div class="col-span-2">
                <flux:radio.group wire:model.live="estado" label="Estado" variant="segmented">
                    <flux:radio label="Todos" value="" />
                    <flux:radio label="Abierto" value="abierto" />
                    <flux:radio label="Pendiente" value="pendiente" />
                    <flux:radio label="Efectivo" value="efectivo" />
                    <flux:radio label="Tarjeta" value="tarjeta" />
                </flux:radio.group>
            </div>
            <div>
                <p class="mb-3">Pedidos en cocina:</p>
                <p class="text-red-500 font-bold text-lg">{{ $this->pedidosCocina }}</p>
            </div>
        </x-mios.caja-filtros>
    </div>

    <x-mios.data-table :headers="['Codigo', 'Fecha', 'Estado', 'Total', 'Camarero', 'Lugar', 'Cocina', 'Pendientes', 'Acciones']">
        @forelse ($this->recibos as $recibo)
            <tr :key="$recibo->id">
                <td class="p-2">{{ $recibo->codigo }}</td>
                <td class="p-2">{{ $recibo->created_at->format('d-m-Y H:i') }}</td>
                <td class="p-2"><flux:badge icon="{{ $recibo->estadoIcon }}" variant="solid" color="{{ $recibo->estadoColor }}">{{ $recibo->estado }}</flux:badge></td>
                <td class="p-2">{{ number_format($recibo->total, 2, ',', '.') }} €</td>
                <td class="p-2">{{ $recibo->user->name }}</td>
                <td class="p-2">
                    {{ $recibo->lugar }}{{ in_array($recibo->lugar, ['Barra', 'Recoger']) ? '' : '-' . $recibo->mesa }}
                </td>
                <td class="p-2"><flux:badge variant="solid" color="{{ $recibo->cocinaColor }}">{{ $recibo->cocina }}</flux:badge></td>
                <td class="p-2">{{ $recibo->pendientes_cocina }}</td>
                <td class="p-2 flex gap-4 justify-center">
                    <flux:button wire:click="show({{ $recibo->id }})" icon="eye" class="cursor-pointer" size="sm" variant="filled">Ver</flux:button>
                    <flux:button icon="printer" variant="primary" class="cursor-pointer" href="{{ route('recibo-pdf', $recibo) }}" target="_blank" size="sm">
                        PDF
                    </flux:button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="p-2">No hay recibos</td>
            </tr>
        @endforelse
    </x-mios.data-table>

    <div class="mt-4">
        {{ $this->recibos->links() }}
    </div>

</x-mios.caja-principal>