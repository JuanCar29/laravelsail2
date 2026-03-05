<x-mios.caja-principal>

    <x-mios.alerta />

    <x-mios.caja-titulo title="Gestión de Reservas">
        <x-mios.create title="Nueva Reserva" />
    </x-mios.caja-titulo>

    <x-mios.caja-filtros>
        <flux:input wire:model.live="fecha_reserva" label="Fecha" class="mb-4" type="date"/>
        <flux:select wire:model.live="turno_reserva" label="Turno" class="mb-4">
            <flux:select.option value="">Seleccione un turno</flux:select.option>
            <flux:select.option value="1">Turno 1</flux:select.option>
            <flux:select.option value="2">Turno 2</flux:select.option>
        </flux:select>
    </x-mios.caja-filtros>

    <x-mios.data-table :headers="['Nombre', 'Teléfono', 'Email', 'Fecha', 'Turno', 'Personas', 'Mesas', 'Confirmada', 'Acciones']">
        @forelse ($this->reservas as $reserva)
            <tr wire:key="reserva-{{ $reserva->id }}">
                <td class="p-2">{{ $reserva->nombre }}</td>
                <td class="p-2">{{ $reserva->telefono }}</td>
                <td class="p-2">{{ $reserva->email }}</td>
                <td class="p-2">{{ $reserva->fecha->format('d-m-Y') }}</td>
                <td class="p-2">Turno {{ $reserva->turno }}</td>
                <td class="p-2">{{ $reserva->personas }}</td>
                <td class="p-2">{{ implode(' | ', $reserva->mesas) }}</td>
                <td class="p-2">
                    <flux:badge variant="solid" color="{{ $reserva->confirmada ? 'green' : 'red' }}">{{ $reserva->confirmada ? 'Sí' : 'No' }}</flux:badge>
                </td>
                <td class="p-2 flex gap-4 justify-center">
                    <flux:button wire:click="edit({{ $reserva->id }})" size="sm" icon="pencil" class="cursor-pointer">
                        Editar
                    </flux:button>
                    <flux:button wire:click="delete({{ $reserva->id }})" size="sm" icon="trash" variant="danger" class="cursor-pointer">
                        Eliminar
                    </flux:button>
                    <flux:button wire:click="confirmar({{ $reserva->id }})" size="sm" icon="check" variant="primary" color="green" class="cursor-pointer" />
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center p-2">No hay reservas</td>
            </tr>
            @endforelse
        </x-mios.data-table>
        
    <x-modales.reservas :mode="$mode" :num_mesas="$this->num_mesas"/>
    <x-modales.delete />
        
</x-mios.caja-principal>
