<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <table class="w-full divide-y divide-gray-200 text-center text-sm text-gray-500 dark:divide-gray-700 dark:text-gray-400">
        <thead class="bg-gray-50 font-semibold uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                @foreach ($headers as $header)
                    <th scope="col" class="p-4">
                        {{ $header }}
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            {{ $slot }}
        </tbody>
    </table>
</div>
