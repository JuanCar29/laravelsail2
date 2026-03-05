<flux:modal name="delete-modal" class="min-w-md">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">Eliminar?</flux:heading>
            <flux:text class="mt-2">
                ¿Estás seguro de eliminar esta reserva?<br>
                Esta acción no se puede revertir.
            </flux:text>
        </div>
        <div class="flex gap-2">
            <flux:spacer />
            <flux:modal.close>
                <flux:button variant="filled" class="cursor-pointer">Cancelar</flux:button>
            </flux:modal.close>
            <flux:button type="submit" variant="danger" class="cursor-pointer" wire:click="deleteConfirm">Eliminar</flux:button>
        </div>
    </div>
</flux:modal>