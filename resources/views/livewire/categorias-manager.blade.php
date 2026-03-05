<x-mios.caja-principal>

    <x-mios.alerta />

    <x-mios.caja-titulo title="Gestión de Categorías" />

    <x-mios.caja-filtros>
        <flux:input type="text" placeholder="Buscar categoría..." wire:model.live.debounce.300ms="search" />
    </x-mios.caja-filtros>

    <x-mios.caja-formulario title="{{ $categoriaId ? 'Editar Categoría' : 'Crear Nueva Categoría' }}">
        <flux:input wire:model="nombre" label="Nombre de la categoría" />
        <flux:textarea wire:model="descripcion" label="Descripción de la categoría" rows="auto" />
        <flux:field>
            <flux:label>Requiere cocina</flux:label>
            <flux:switch wire:model="cocina" />
        </flux:field>
        <div class="flex flex-wrap gap-4">
            <flux:button wire:click="save" class="cursor-pointer" size="sm" variant="primary">
                {{ $categoriaId ? 'Actualizar Categoría' : 'Crear Categoría' }}
            </flux:button>
            @if ($categoriaId)
                <flux:button wire:click="cancel" class="cursor-pointer" size="sm" variant="filled">
                    Cancelar
                </flux:button>
            @endif
        </div>
    </x-mios.caja-formulario>

    <x-mios.data-table :headers="['Nombre', 'Descripción','Requiere cocina','Acciones']">
        @forelse ($this->categorias as $categoria)
            <tr wire:key="categoria-{{ $categoria->id }}">
                <td class="p-2">{{ $categoria->nombre }}</td>
                <td class="p-2">{{ $categoria->descripcion }}</td>
                <td class="p-2">
                    @if ($categoria->cocina)
                        <span class="text-green-500">Sí</span>
                    @else
                        <span class="text-red-500">No</span>
                    @endif
                </td>
                <td class="p-2">
                    <flux:button wire:click="edit({{ $categoria->id }})" class="cursor-pointer" size="sm" icon="pencil-square"
                        variant="filled">
                        Editar
                    </flux:button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center text-gray-500 p-2">No hay categorías.</td>
            </tr>
        @endforelse
    </x-mios.data-table>

    <div class="mt-4">
        {{ $this->categorias->links() }}
    </div>

</x-mios.caja-principal>
