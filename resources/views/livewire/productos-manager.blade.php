<x-mios.caja-principal>

    <x-mios.alerta />

    <x-mios.caja-titulo title="Gestión de Productos" />

    <x-mios.caja-filtros>
        <flux:input type="text" placeholder="Buscar producto..." wire:model.live.debounce.300ms="search"
            label="Buscar producto" />
        <flux:select wire:model.live="buscar_categoria" label="Filtrar por categoría">
            <flux:select.option value=''>Todas las categorías</flux:select.option>
            @foreach ($this->categorias as $categoria)
                <flux:select.option :value="$categoria->id">
                    {{ $categoria->nombre }}
                </flux:select.option>
            @endforeach
        </flux:select>
    </x-mios.caja-filtros>

    <x-mios.caja-formulario title="{{ $productoId ? 'Editar Producto' : 'Crear Nuevo Producto' }}">
        <flux:input wire:model="nombre" label="Nombre del producto" placeholder="Ej: Café con leche" />

        <flux:textarea wire:model="descripcion" label="Descripción (opcional)" rows="auto"
            placeholder="Ingredientes, alérgenos, etc." />

        <flux:select wire:model="categoria_id" label="Categoría">
            <flux:select.option value=''>Selecciona una categoría</flux:select.option>
            @foreach ($this->categorias as $categoria)
                <flux:select.option :value="$categoria->id">
                    {{ $categoria->nombre }}
                </flux:select.option>
            @endforeach
        </flux:select>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <flux:input type="number" step="0.01" min="0" wire:model="precio" label="Precio (€)"
                placeholder="0.00" />

            <flux:select wire:model="iva_id" label="Tipo de IVA">
                <flux:select.option value=''>Tipo...</flux:select.option>
                @foreach ($this->ivas as $iva)
                    <flux:select.option :value="$iva->id">
                        {{ $iva->nombre }}( {{ $iva->valor }} % )
                    </flux:select.option>
                @endforeach
            </flux:select>
        </div>

        <div class="flex flex-wrap gap-3">
            <flux:checkbox wire:model="destacado" label="Destacado"
                description="Mostrar en sección destacada del menú" />

            <flux:checkbox wire:model="activo" label="Activo" description="Visible en menú y pedidos" />
        </div>

        <flux:input type="number" min="0" wire:model="orden" label="Orden (opcional)" placeholder="1, 2, 3..."
            description="Determina la posición en el menú" />

        <div class="flex flex-wrap gap-4">
            <flux:button wire:click="save" class="cursor-pointer" size="sm" variant="primary">
                {{ $productoId ? 'Actualizar Producto' : 'Crear Producto' }}
            </flux:button>

            @if ($productoId)
                <flux:button wire:click="cancel" class="cursor-pointer" size="sm" variant="filled">
                    Cancelar
                </flux:button>
            @endif
        </div>
    </x-mios.caja-formulario>

    <x-mios.data-table :headers="['Orden', 'Nombre', 'Categoría', 'Precio', 'IVA', 'Estado', 'Acciones']">
        @forelse ($this->productos as $producto)
            <tr wire:key="producto-{{ $producto->id }}">
                <td class="p-2 font-medium">{{ $producto->orden ?? '—' }}</td>
                <td class="p-2 font-medium">{{ $producto->nombre }}</td>
                <td class="p-2">
                    {{ $producto->categoria?->nombre ?? '—' }}
                </td>
                <td class="p-2">{{ number_format($producto->precio, 2, ',', '.') }} €</td>
                <td class="p-2">{{ number_format($producto->iva->valor, 2, ',', '.') }} %</td>
                <td class="p-2 justify-center gap-4">
                    @if ($producto->activo)
                        <flux:badge variant="solid" color="sky">Activo</flux:badge>
                    @else
                        <flux:badge variant="solid" color="zinc">Inactivo</flux:badge>
                    @endif
                    @if ($producto->destacado)
                        <flux:badge variant="solid" color="green">Destacado</flux:badge>
                    @endif
                </td>
                <td class="p-2">
                    <flux:button wire:click="edit({{ $producto->id }})" class="cursor-pointer" size="sm" icon="pencil-square"
                        variant="filled">
                        Editar
                    </flux:button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center text-gray-500 p-2">No hay productos.</td>
            </tr>
        @endforelse
    </x-mios.data-table>

    <div class="mt-4">
        {{ $this->productos->links() }}
    </div>

</x-mios.caja-principal>
