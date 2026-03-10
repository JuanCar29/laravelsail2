<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4">
        <div class="relative h-full flex-1 overflow-hidden rounded-2xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-orange-400 to-orange-600"></div>

            <div class="flex flex-col h-full w-full items-center justify-center p-8">
                <div class="mb-8 flex items-center justify-center p-4 rounded-full bg-neutral-50 dark:bg-neutral-800 shadow-inner">
                    @if($miBar)
                        <img src="{{ asset('storage/' . $miBar->logo) }}" 
                            alt="Logo" 
                            class="w-64 h-64 object-contain drop-shadow-md">
                    @endif
                </div>

                <div class="text-center">
                    <h1 class="text-5xl font-extrabold text-neutral-800 dark:text-neutral-100 tracking-tight">
                        {{ auth()->user()->name }}
                    </h1>
                    <p class="text-xl text-neutral-500 dark:text-neutral-400 mt-2 font-medium">
                        {{ auth()->user()->email }}
                    </p>
                </div>

                <div class="w-16 h-px bg-neutral-300 dark:bg-neutral-600 my-8"></div>

                <div class="flex gap-8 text-center">
                    <div>
                        <p class="text-xs uppercase tracking-widest text-neutral-400 mb-1">Fecha</p>
                        <p class="text-2xl font-semibold text-neutral-700 dark:text-neutral-200">
                            {{ now()->format('d · m · Y') }}
                        </p>
                    </div>
                    <div class="border-l border-neutral-200 dark:border-neutral-700 h-10"></div>
                    <div>
                        <p class="text-xs uppercase tracking-widest text-neutral-400 mb-1">Hora</p>
                        <p class="text-xs uppercase tracking-widest text-neutral-400 mb-1">{{ config('app.timezone') }}</p>
                        <p class="text-2xl font-mono font-bold text-orange-500">
                            {{ now()->format('H:i') }}<span class="text-lg opacity-75">:{{ now()->format('s') }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
