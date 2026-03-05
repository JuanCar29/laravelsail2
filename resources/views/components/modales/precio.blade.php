<flux:modal name="edit-precio-{{ $lineaRecibo->id }}" wire:close="resetPrecio">
    <p class="text-lg font-medium text-gray-900 dark:text-white">Editar precio</p>
    <p class="text-lg font-bold text-gray-900 dark:text-white mb-4">{{ $lineaRecibo->nombre_producto }}</p>
    <flux:input wire:model="nuevoPrecio" label="Nuevo precio" />
    <div class="grid grid-cols-3 gap-4 my-4">
        @for($i = 1; $i <= 9; $i++)
            <flux:button wire:click="precio({{ $i }})" size="sm" variant="filled" class="cursor-pointer">{{ $i }}</flux:button>
        @endfor
        <flux:button wire:click="precio(0)" size="sm" variant="filled" class="cursor-pointer">0</flux:button>
        <flux:button wire:click="precio('.')" size="sm" variant="filled" class="cursor-pointer">.</flux:button>
        <flux:button wire:click="precio('C')" size="sm" variant="danger" class="cursor-pointer">C</flux:button>
    </div>
    <div class="flex justify-end">
        <flux:button wire:click="editarPrecio({{ $lineaRecibo->id }})" size="sm" variant="primary" class="cursor-pointer my-4">Guardar</flux:button>
    </div>
</flux:modal>