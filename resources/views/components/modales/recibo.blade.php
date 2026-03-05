<flux:modal name="edit-recibo" class="min-w-xl">
    <p class="text-lg font-medium text-gray-900 dark:text-white">Datos del recibo</p>
    <p class="text-lg font-bold text-gray-900 dark:text-white mb-4">{{ $recibo->codigo ?? '' }}</p>
    <flux:input wire:model="codigo" label="Código" class="mb-4"/>
    <flux:select wire:model="user_id" label="Usuario" class="mb-4">
        @foreach ($this->camareros as $user)
            <flux:select.option value="{{ $user->id }}">{{ $user->name }}</flux:select.option>
        @endforeach
    </flux:select>
    <div class="flex justify-end">
        <flux:button wire:click="save" size="sm" variant="primary" class="cursor-pointer my-4">Guardar</flux:button>
    </div>
</flux:modal>