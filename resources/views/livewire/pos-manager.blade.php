<div class="p-4 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-6 gap-4">
    <div class="xl:col-span-4 p-4 bg-white rounded-lg shadow">
        <x-mios.caja-titulo title="Productos">
            <p class="text-2xl font-bold text-right">{{ $this->nombreCategoriaSeleccionada }}</p>
        </x-mios.caja-titulo>
        <flux:separator />
        @if ($recibo->estado === 'Abierto')
            <div class="grid grid-cols-2 xl:grid-cols-4 2xl:grid-cols-6 p-4 gap-4">
                <flux:button wire:click="buscarCategoria(0)" class="cursor-pointer" size="sm" variant="danger">
                    Favoritos
                </flux:button>
                @forelse ($this->categorias as $categoria)
                    <flux:button wire:click="buscarCategoria({{ $categoria->id }})" class="cursor-pointer" size="sm" variant="primary">
                        {{ $categoria->nombre }}
                    </flux:button>
                @empty
                    <p>No hay categorias</p>
                @endforelse
            </div>
            <flux:separator />
            <div class="grid grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 3xl:grid-cols-6 p-4 gap-4">
                @forelse ($this->productos as $producto)
                    <x-mios.caja-producto 
                        :producto="$producto" 
                        wire:click="agregarProductoConId({{ $producto->id }})"
                    />
                @empty
                    <p>No hay productos</p>
                @endforelse
            </div>
            <div class="p-4">
                {{ $this->productos->links() }}
            </div>
        @endif
    </div>
    <div class="xl:col-span-2 p-4 bg-white rounded-lg shadow">
        <x-mios.alerta/>
        <x-mios.caja-titulo title="Detalles del recibo" >
            <p class="font-bold text-lg text-center">{{ $recibo->user->name }}</p>
            <p class="text-2xl font-bold text-right">{{ number_format($recibo->total, 2, ',', '.') }} €</p>
        </x-mios.caja-titulo>
        @if ($recibo->dato)
            <x-mios.caja-filtros>
                <p>Datos de recogida</p>
                <p>{{ $recibo->dato->telefono }}</p>
                <p>{{ $recibo->dato->email }}</p>
            </x-mios.caja-filtros>
        @endif
        <flux:separator />
        <div class="grid grid-cols-1 2xl:grid-cols-3 items-center gap-2 my-4">
            <p class="font-bold text-lg text-center">{{ $recibo->codigo }}</p>
            <p class="font-bold text-lg text-center">{{ $recibo->created_at->format('d-m-Y H:i') }}</p>
            <div class="flex justify-center">
                <flux:badge icon="{{ $recibo->estadoIcon }}" variant="solid" color="{{ $recibo->estadoColor }}">{{ $recibo->estado }}</flux:badge>
            </div>
        </div>
        <div class="grid grid-cols-1 2xl:grid-cols-3 items-end gap-2 mb-4">
            <flux:select wire:model="mesa" label="Mesa">
                @for ($i = 1; $i <= $totalMesas; $i++)
                    <flux:select.option value="{{ $i }}">{{ $i }}</flux:select.option>
                @endfor
            </flux:select>
            <flux:select wire:model="lugar" label="Lugar">
                <flux:select.option value="Barra">Barra</flux:select.option>
                <flux:select.option value="Mesa">Mesa</flux:select.option>
                <flux:select.option value="Terraza">Terraza</flux:select.option>
                <flux:select.option value="Recoger">Recoger</flux:select.option>
            </flux:select>
            <flux:button icon="envelope" variant="filled" class="w-full cursor-pointer" wire:click="abrirModal">Datos</flux:button>
        </div>
        @if ($recibo->estado === 'Abierto' && $recibo->total > 0)
            <div class="grid grid-cols-1 2xl:grid-cols-3 gap-8 mb-4">
                <flux:button icon="clock" variant="primary" color="red" class="w-full cursor-pointer" wire:click="actualizarEstado('pendiente')">Pendiente</flux:button>
                <flux:button icon="currency-euro" variant="primary" color="green" class="w-full cursor-pointer" wire:click="actualizarEstado('efectivo')">Efectivo</flux:button>
                <flux:button icon="credit-card" variant="primary" color="blue" class="w-full cursor-pointer" wire:click="actualizarEstado('tarjeta')">Tarjeta</flux:button>          
            </div>
        @endif
        @if ($recibo->estado != 'Abierto')
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-10 mb-4">
                <flux:button icon="plus-circle" variant="primary" color="zinc" class="w-full cursor-pointer" wire:click="create">Nuevo ticket</flux:button>
                @if ($this->recibosPendientes > 0)
                    <flux:button icon="eye" variant="primary" color="red" class="w-full cursor-pointer" href="{{ route('recibos') .'?estado=pendiente' }}">
                        Ver pendientes ({{ $this->recibosPendientes }})
                    </flux:button>
                @endif          
            </div>
        @endif
        <flux:separator class="mb-4"/>
        <x-mios.data-table :headers="['Cantidad', 'Producto', 'Precio', 'Subtotal', 'Acciones']">
            @forelse ($this->lineasRecibo as $lineaRecibo)
                <tr :key="$lineaRecibo->id">
                    <td>{{ $lineaRecibo->cantidad }}</td>
                        <td class="p-2">{{ $lineaRecibo->nombre_producto }}</td>
                        <td class="p-2">{{ number_format($lineaRecibo->precio_unitario, 2, ',', '.') }} €</td>
                        <td class="p-2">{{ number_format($lineaRecibo->subtotal, 2, ',', '.') }} €</td>
                        <td class="p-2 flex justify-end gap-4">
                            @if ($lineaRecibo->cantidad > 1)
                                <flux:button wire:click="quitarCantidad({{ $lineaRecibo->id }})" icon="minus-circle" class="cursor-pointer" size="sm" />
                            @endif
                            <flux:button wire:click="quitarProducto({{ $lineaRecibo->id }})" icon="trash" class="cursor-pointer" size="sm" variant="danger" />
                            <flux:modal.trigger name="edit-precio-{{ $lineaRecibo->id }}">
                                <flux:button icon="pencil-square" class="cursor-pointer" size="sm" variant="filled" />
                            </flux:modal.trigger>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-4 text-center text-lg">No hay productos</td>
                    </tr>
                @endforelse
        </x-mios.data-table>
        @if (in_array($recibo->estado, ['Efectivo', 'Tarjeta']))
            <flux:button icon="printer" variant="primary" class="w-full cursor-pointer mt-4" href="{{ route('recibo-pdf', $recibo) }}" target="_blank">
                Generar PDF
            </flux:button>
        @endif
    </div>
    @foreach ($this->lineasRecibo as $lineaRecibo)
        <x-modales.precio :lineaRecibo="$lineaRecibo" />
    @endforeach
    <x-modales.datos :recibo="$recibo"/>
</div>
