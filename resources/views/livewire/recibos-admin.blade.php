<x-mios.caja-principal>

    <x-mios.alerta />

    <x-mios.caja-titulo title="Recibos">
        <x-mios.create title="Nuevo Recibo" />
    </x-mios.caja-titulo>

    <x-mios.caja-filtros>
        <flux:input wire:model.live="dia" type="date" label="Dia recibo" />
        <flux:select wire:model.live="estado" label="Estado">
            <flux:select.option value="">Todos</flux:select.option>
            <flux:select.option value="abierto">Abierto</flux:select.option>
            <flux:select.option value="pendiente">Pendiente</flux:select.option>
            <flux:select.option value="efectivo">Efectivo</flux:select.option>
            <flux:select.option value="tarjeta">Tarjeta</flux:select.option>
        </flux:select>
        <flux:select wire:model.live="camarero" label="Camarero">
            <flux:select.option value="">Todos</flux:select.option>
            @foreach ($this->camareros as $camarero)
                <flux:select.option :value="$camarero->id">{{ $camarero->name }}</flux:select.option>
            @endforeach
        </flux:select>
    </x-mios.caja-filtros>

    <x-mios.data-table :headers="['Codigo', 'Fecha', 'Estado', 'Total', 'Camarero', 'Cocina', 'Pendientes', 'Acciones']">
        @forelse ($this->recibos as $recibo)
            <tr :key="$recibo->id">
                <td class="p-2">{{ $recibo->codigo }}</td>
                <td class="p-2">{{ $recibo->created_at->format('d-m-Y H:i') }}</td>
                <td class="p-2"><flux:badge icon="{{ $recibo->estadoIcon }}" variant="solid" color="{{ $recibo->estadoColor }}">{{ $recibo->estado }}</flux:badge></td>
                <td class="p-2">{{ number_format($recibo->total, 2, ',', '.') }} €</td>
                <td class="p-2">{{ $recibo->user->name }}</td>
                <td class="p-2"><flux:badge variant="solid" color="{{ $recibo->cocinaColor }}">{{ $recibo->cocina }}</flux:badge></td>
                <td class="p-2">{{ $recibo->pendientes_cocina }}</td>
                <td class="p-2 flex gap-4 justify-center">
                    <flux:button wire:click="show({{ $recibo->id }})" icon="eye" class="cursor-pointer" size="sm" variant="filled">Ver</flux:button>
                    <flux:button wire:click="edit({{ $recibo->id }})" icon="pencil" class="cursor-pointer" size="sm">Editar</flux:button>
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

    <x-modales.recibo :recibo="$recibo" />

</x-mios.caja-principal>