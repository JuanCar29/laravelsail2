<flux:modal name="ver-pedido" class="min-w-2xl">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">Pedido {{ $codigo }}</flux:heading>
            <flux:text class="mt-2">
                Hora del pedido: {{ $fecha }}
            </flux:text>
        </div>
        <x-mios.data-table :headers="['Cantidad', 'Producto', 'Precio', 'Total', 'Listo', 'Acción']">
            @forelse($this->lineas as $linea)
                <tr wire:key="{{ $linea->id }}">
                    <td class="p-2">{{ $linea->cantidad }}</td>
                    <td class="p-2">{{ $linea->producto->nombre }}</td>
                    <td class="p-2">{{ number_format($linea->precio_unitario, 2, ',', '.') }} €</td>
                    <td class="p-2">{{ number_format($linea->subtotal, 2, ',', '.') }} €</td>
                    <td class="p-2">
                        <flux:badge variant="solid" color="{{ $linea->listo ? 'green' : 'red' }}">
                            {{ $linea->listo ? 'Ok' : '-' }}
                        </flux:badge>
                    </td>
                    <td class="p-2">
                        <flux:button wire:click="terminado({{ $linea->id }})" class="cursor-pointer" size="sm" icon="check-circle" />
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="p-2">No hay detalles</td>
                </tr>
            @endforelse
        </x-mios.data-table>
        <div class="flex gap-2">
            <flux:spacer />
            <flux:modal.close>
                <flux:button variant="filled" class="cursor-pointer">Cerrar</flux:button>
            </flux:modal.close>
            <flux:button wire:click="listo({{ $reciboId }})" variant="primary" class="cursor-pointer">Listo</flux:button>
        </div>
    </div>
</flux:modal>