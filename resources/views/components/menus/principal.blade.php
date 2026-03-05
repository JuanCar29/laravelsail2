<flux:navbar class="-mb-px max-lg:hidden">
    <flux:navbar.item :href="route('home')" icon="home">Home</flux:navbar.item>
    <flux:navbar.item :href="route('libros.catalogo')" icon="book-open">Libros</flux:navbar.item>
    @auth
        <flux:navbar.item :href="route('dashboard')" icon="user-circle">Mi cuenta</flux:navbar.item>
    @else
        <flux:navbar.item :href="route('login')" icon="arrow-right-end-on-rectangle">Acceso</flux:navbar.item>
    @endauth
</flux:navbar>
