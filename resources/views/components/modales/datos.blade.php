<flux:modal name="datos-recogida" class="min-w-xl">
    <p class="text-lg font-medium text-gray-900 dark:text-white">Datos de recogida</p>
    <p class="text-lg font-bold text-gray-900 dark:text-white mb-4">{{ $recibo->codigo }}</p>
    <flux:input wire:model="telefono" label="Teléfono" class="mb-4"/>
    <flux:input wire:model="email" label="Email" class="mb-4"/>
    <div class="flex justify-end">
        <flux:button wire:click="guardarDatosRecogida" size="sm" variant="primary" class="cursor-pointer my-4">Guardar</flux:button>
    </div>
</flux:modal>