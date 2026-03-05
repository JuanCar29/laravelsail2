<x-mios.caja-principal>

    <x-mios.alerta />

    <x-mios.caja-titulo title="Gestión de Usuarios" />

    <x-mios.caja-formulario :title="$userId ? 'Editar Usuario' : 'Nuevo Usuario'">
        <flux:input wire:model="name" label="Nombre" />
        <flux:input wire:model="email" label="Email" />
        <flux:input wire:model="nivel" label="Nivel" type="number" step="1" min="1" max="3"/>
        <flux:field>
            <flux:label>Activo</flux:label>
            <flux:switch wire:model="activo" />
        </flux:field>
        <flux:button wire:click="save" variant="primary" size="sm" class="cursor-pointer">{{ $userId ? 'Actualizar' : 'Nuevo Usuario' }}</flux:button>
        <flux:button wire:click="cancel" variant="filled" size="sm" class="cursor-pointer">Cancelar</flux:button>
    </x-mios.caja-formulario>

    <x-mios.data-table :headers="['Nombre', 'Email', 'Nivel', 'Activo', 'Acciones']">
        @forelse ($this->users as $user)
            <tr wire:key="user-{{ $user->id }}">
                <td class="p-2">{{ $user->name }}</td>
                <td class="p-2">{{ $user->email }}</td>
                <td class="p-2">{{ $user->nivel }}</td>
                <td class="p-2">
                    <flux:badge variant="solid" color="{{ $user->activo ? 'green' : 'red' }}">{{ $user->activo ? 'Sí' : 'No' }}</flux:badge>
                </td>
                <td class="p-2">
                    <flux:button wire:click="edit({{ $user }})" icon="pencil-square" size="sm" class="cursor-pointer" variant="filled">
                        Editar
                    </flux:button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="p-2 text-center">No hay usuarios</td>
            </tr>
        @endforelse
    </x-mios.data-table>

    <div class="mt-4">
        {{ $this->users->links() }}
    </div>

</x-mios.caja-principal>

    
