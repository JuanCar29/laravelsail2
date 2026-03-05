<x-mios.caja-principal>

    <x-mios.alerta />

    <x-mios.caja-titulo title="Pedidos en cocina" />

    <x-mios.caja-filtros>
        <flux:input wire:model.live="dia" type="date" label="Dia pedido"/>
        <flux:radio.group wire:model.live="estado" label="Estado pedido" variant="segmented">
            <flux:radio label="Pendiente" value="pendiente"/>
            <flux:radio label="Cocinando" value="cocinando"/>
            <flux:radio label="Listo" value="listo"/>
        </flux:radio.group>
    </x-mios.caja-filtros>

    <div wire:poll.20s>
        <x-mios.data-table :headers="['Orden', 'Pedido', 'Fecha', 'Estado', 'Camarero', 'Pendientes', 'Acciones']">
            @forelse($this->recibos as $recibo)
                <tr wire:key="{{ $recibo->id }}">
                    <td class="p-2">{{ $loop->iteration }}</td>
                    <td class="p-2">{{ $recibo->codigo }}</td>
                    <td class="p-2">{{ $recibo->created_at->format('H:i:s') }}</td>
                    <td class="p-2"><flux:badge variant="solid" color="{{ $recibo->CocinaColor }}">{{ $recibo->cocina }}</flux:badge></td>
                    <td class="p-2">{{ $recibo->user->name }}</td>
                    <td class="p-2">{{ $recibo->pendientes_cocina }}</td>
                    <td class="p-2">
                        <flux:button wire:click="show({{ $recibo->id }})" icon="eye" size="sm" class="cursor-pointer" variant="filled">
                            Ver pedido
                        </flux:button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="p-2">No hay pedidos</td>
                </tr>
            @endforelse
        </x-mios.data-table>
    </div>

    <div class="mt-4">
        {{ $this->recibos->links() }}
    </div>

    <x-modales.cocina :codigo="$codigo" :fecha="$fecha" :reciboId="$reciboId"/>

</x-mios.caja-principal>