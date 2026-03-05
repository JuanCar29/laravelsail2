<flux:navlist variant="outline">
    <flux:navlist.group :heading="__('Platform')" class="grid">
        <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')"
            wire:navigate>{{ __('Dashboard') }}
        </flux:navlist.item>
        @can('camarero')
            <flux:navlist.item icon="ticket" :href="route('recibos')" :current="request()->routeIs('recibos')"
                wire:navigate>{{ __('Recibos') }}
            </flux:navlist.item>
            <flux:navlist.item icon="clock" :href="route('reservas')" :current="request()->routeIs('reservas')"
                wire:navigate>{{ __('Reservas') }}
            </flux:navlist.item>
        @endcan
        @can('cocinero')
            <flux:navlist.item icon="gift" :href="route('cocinas')" :current="request()->routeIs('cocinas')"
                wire:navigate>{{ __('Cocinas') }}
            </flux:navlist.item>
        @endcan
    </flux:navlist.group>
    @can('admin')
        <flux:navlist.group :heading="__('Settings')" class="grid">
            <flux:navlist.item icon="tag" :href="route('categorias')" :current="request()->routeIs('categorias')"
                wire:navigate>{{ __('Categorias') }}</flux:navlist.item>
            <flux:navlist.item icon="archive-box" :href="route('productos')" :current="request()->routeIs('productos')"
                wire:navigate>{{ __('Productos') }}</flux:navlist.item>
            <flux:navlist.item icon="users" :href="route('users')" :current="request()->routeIs('users')"
                wire:navigate>{{ __('Users') }}</flux:navlist.item>
            <flux:navlist.item icon="currency-euro" :href="route('ivas')" :current="request()->routeIs('ivas')"
                wire:navigate>{{ __('Ivas') }}</flux:navlist.item>
            <flux:navlist.item icon="cog-8-tooth" :href="route('preferencias')" :current="request()->routeIs('preferencias')"
                wire:navigate>{{ __('Preferencias') }}</flux:navlist.item>
        </flux:navlist.group>
        <flux:navlist.group :heading="__('Statistics')">
            <flux:navlist.item icon="ticket" :href="route('recibos-admin')" wire:navigate>{{ __('Recibos') }}</flux:navlist.item>
            <flux:navlist.item icon="clock" :href="route('registros')" wire:navigate>{{ __('Registros') }}</flux:navlist.item>
            <flux:navlist.item icon="clipboard-document-list" :href="route('ventas')" wire:navigate>{{ __('Ventas') }}</flux:navlist.item>
            <flux:navlist.item icon="currency-euro" :href="route('caja')">{{ __('Caja') }}</flux:navlist.item>
            <flux:navlist.item icon="currency-euro" :href="route('cajas')">{{ __('Cajas') }}</flux:navlist.item>
        </flux:navlist.group>
    @endcan
</flux:navlist>
