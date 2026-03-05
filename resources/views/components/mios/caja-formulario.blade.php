<section class="mb-4 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg shadow-md">
    <h5 class="mb-2 text-3xl font-bold text-gray-900 dark:text-white">{{ $title }}</h5>
    <div class="mt-4 grid grid-cols-1 gap-4 lg:grid-cols-4 items-end">
        {{ $slot }}
    </div>
</section>
