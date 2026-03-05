<flux:modal name="reservas-modal" class="w-md md:w-xl xl:max-w-2xl" wire:close="resetReserva">
    <p class="text-lg font-medium text-gray-900 dark:text-white">{{ $mode ? 'Nueva Reserva' : 'Editar Reserva' }}</p>
    <flux:input wire:model="nombre" label="Nombre" class="mb-4"/>
    <flux:input wire:model="telefono" label="Teléfono" class="mb-4" mask="999 999 999"/>
    <flux:input wire:model="email" label="Email" class="mb-4"/>
    <flux:input wire:model="fecha" label="Fecha" class="mb-4" type="date"/>
    <div class="grid grid-cols-3 gap-4">
        <div class="col-span-2">
            <flux:radio.group wire:model="turno" label="Turno" variant="segmented" class="mb-4">
                <flux:radio label="Turno 1" value="1" />
                <flux:radio label="Turno 2" value="2" />
            </flux:radio.group>
        </div>
        <flux:input wire:model="personas" label="Personas" class="mb-4" type="number"/>
    </div>
    <flux:checkbox.group wire:model="mesas" label="Mesas de reservas" class="mb-4">
        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-5 gap-4">
            @for ($i = 1; $i <= $this->num_mesas; $i++)
                <flux:checkbox value="{{ $i }}" label="Mesa {{ $i }}"/>
            @endfor
        </div>
    </flux:checkbox.group>
    <flux:field class="mb-4">
        <flux:label>Confirmada</flux:label>
        <flux:switch wire:model="confirmada" />
    </flux:field>
    <div class="flex justify-end gap-4 mb-4">
        <flux:modal.close>
            <flux:button size="sm" class="cursor-pointer">Cancelar</flux:button>
        </flux:modal.close> 
        <flux:button wire:click="save" size="sm" variant="primary" class="cursor-pointer">
            {{ $mode ? 'Guardar' : 'Actualizar' }}
        </flux:button>
    </div>
    <div wire:dirty class="text-red-500">Cambios sin guardar...</div>
</flux:modal>