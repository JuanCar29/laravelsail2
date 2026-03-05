<flux:navlist class="w-48">
    <flux:navlist.item :href="route('home')" icon="home">Home</flux:navlist.item>
    <flux:navlist.item href="#" icon="book-open">Libros</flux:navlist.item>
    @auth
        <flux:navlist.item :href="route('dashboard')" icon="user-circle">Mi cuenta</flux:navlist.item>
    @else
        <flux:navlist.item :href="route('login')" icon="arrow-right-end-on-rectangle">Acceso</flux:navlist.item>
    @endauth
</flux:navlist>
