<x-mios.caja-principal>

    <x-mios.alerta />

    <x-mios.caja-titulo title="Administración de IVA"/>

    <x-mios.caja-formulario :title="$ivaId ? 'Editar IVA' : 'Crear nuevo IVA'">
        <flux:input wire:model="nombre" label="Nombre del IVA" />
        <flux:input wire:model="valor" label="Valor del IVA" type="number" step="0.01" />
        <flux:field>
            <flux:label>Defecto</flux:label>
            <flux:switch wire:model="defecto" />
        </flux:field>
        <div class="flex flex-wrap gap-4">
            <flux:button wire:click="save" class="cursor-pointer" size="sm" variant="primary">
                {{ $ivaId ? 'Actualizar IVA' : 'Crear IVA' }}
            </flux:button>
            @if ($ivaId)
                <flux:button wire:click="cancel" class="cursor-pointer" size="sm" variant="filled">
                    Cancelar
                </flux:button>
            @endif
        </div>
    </x-mios.caja-formulario>

    <x-mios.data-table :headers="['Nombre', 'Valor', 'Defecto', 'Acciones']">
        @forelse($this->ivas as $iva)
            <tr>
                <td class="p-2">{{ $iva->nombre }}</td>
                <td class="p-2">{{ number_format($iva->valor, 2, ',', '.') }} %</td>
                <td class="p-2">
                    <flux:badge variant="solid" color="{{ $iva->defecto ? 'green' : 'red' }}">{{ $iva->defecto ? 'Sí' : 'No' }}</flux:badge>
                </td>
                <td class="p-2">
                    <flux:button wire:click="edit({{ $iva->id }})" size="sm" class="cursor-pointer" icon="pencil-square" variant="filled">Editar</flux:button>                  
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="p-2">No hay IVA</td>
            </tr>
        @endforelse
    </x-mios.data-table>
    
</x-mios.caja-principal>
