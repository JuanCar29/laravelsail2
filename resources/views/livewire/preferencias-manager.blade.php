<x-mios.caja-principal>

    <x-mios.alerta />

    <x-mios.caja-titulo title="Gestión de Preferencias" />

    <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
        <form wire:submit.prevent="savePreferencias" class="space-y-4">

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <flux:input wire:model="nombre" label="Nombre negocio" />
                <flux:input wire:model="direccion" label="Dirección" />
                <flux:input wire:model="telefono" label="Teléfono" />
                <flux:input wire:model="email" label="Email" />
                <flux:input wire:model="logo" label="Logo negocio" type="file" />
                <flux:input wire:model="cp" label="Código Postal" />
                <flux:input wire:model="ciudad" label="Ciudad" />
                <flux:input wire:model="provincia" label="Provincia" />
                <flux:input wire:model="cif" label="CIF" />
                <flux:input wire:model="prefijo" label="Prefijo" />
                <flux:input wire:model="siguiente" label="Siguiente Número" />
                <flux:input wire:model="mesas" label="Nº de Mesas" type="number" />
            </div>

            <div>
                <flux:button type="submit" size="sm" class="cursor-pointer" variant="primary" icon="check-circle">
                    Guardar Preferencias
                </flux:button>
            </div>
        </form>
    </div>

</x-mios.caja-principal>
