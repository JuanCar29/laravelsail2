<x-mios.caja-principal>

    <x-mios.alerta />

    <x-mios.caja-titulo title="Listado de Registros"/>

    <x-mios.caja-filtros>
        <flux:select wire:model.live="buscar_user" label="Usuario">
            <flux:select.option value="">Seleccione un Usuario</flux:select.option>
            @foreach($this->camareros as $user)
                <flux:select.option value="{{ $user->id }}">{{ $user->name }}</flux:select.option>
            @endforeach
        </flux:select>
        <flux:input wire:model.live="buscar_desde" label="Desde" type="date" />
        <flux:input wire:model.live="buscar_hasta" label="Hasta" type="date" />
    </x-mios.caja-filtros>

    <x-mios.caja-formulario :title="$registroId ? 'Editar Registro' : 'Crear nuevo Registro'">
        <flux:select wire:model="user_id" label="Usuario">
            <flux:select.option value="">Seleccione un Usuario</flux:select.option>
            @foreach($this->camareros as $user)
                <flux:select.option value="{{ $user->id }}">{{ $user->name }}</flux:select.option>
            @endforeach
        </flux:select>
        <flux:input wire:model="entrada" label="Entrada" type="datetime-local" />
        <flux:input wire:model="salida" label="Salida" type="datetime-local" />
        <div class="flex flex-wrap gap-4">
            <flux:button wire:click="save" class="cursor-pointer" size="sm" variant="primary">
                {{ $registroId ? 'Actualizar Registro' : 'Crear Registro' }}
            </flux:button>
            @if ($registroId)
                <flux:button wire:click="cancel" class="cursor-pointer" size="sm" variant="filled">
                    Cancelar
                </flux:button>
            @endif
        </div>
    </x-mios.caja-formulario>

    <x-mios.data-table :headers="['Usuario', 'Día', 'Entrada', 'Salida', 'Total', 'Acciones']">
        @forelse($this->registros as $registro)
            <tr>
                <td class="p-2">{{ $registro->user->name }}</td>
                <td class="p-2">{{ $registro->entrada->format('d-m-Y') }}</td>
                <td class="p-2">{{ $registro->entrada->format('H:i') }}</td>
                <td class="p-2">{{ $registro->salida ? $registro->salida->format('H:i') : 'Sin Salida' }}</td>
                <td class="p-2">{{ $registro->salida ? $registro->horas : 'Sin Salida' }}</td>
                <td class="p-2 flex justify-center gap-2">
                    <flux:button wire:click="edit({{ $registro->id }})" size="sm" class="cursor-pointer" icon="pencil-square" variant="filled">
                        Editar
                    </flux:button>                  
                    <flux:button wire:click="delete({{ $registro->id }})" size="sm" class="cursor-pointer" icon="trash" variant="danger">
                        Eliminar
                    </flux:button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="p-2">No hay Registros</td>
            </tr>
        @endforelse
    </x-mios.data-table>

    <div class="mt-4">
        {{ $this->registros->links() }}
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 mt-4">
        @foreach($this->horasTrabajadas as $item)
            <div class="p-4 bg-white rounded shadow">
                <h3 class="font-bold text-lg">{{ $item->user->name }}</h3>
                <p class="text-md">{{ $item->total_registros }} registros</p>
                {{-- Convertimos los minutos a formato 00h 00m --}}
                <p class="text-md">
                    {{ floor($item->total_minutos / 60) }} h 
                    {{ ($item->total_minutos % 60) }} m
                </p>
            </div>
        @endforeach
    </div>
    
</x-mios.caja-principal>
