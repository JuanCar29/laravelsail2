<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Error' }} - 2sin3</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-neutral-50 dark:bg-neutral-950 flex items-center justify-center min-h-screen p-4 font-sans">
    <div class="max-w-xl w-full">
        <div class="relative overflow-hidden rounded-3xl border border-neutral-200 bg-white shadow-2xl dark:border-neutral-800 dark:bg-neutral-900">
            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r {{ $color ?? 'from-orange-400 to-orange-600' }}"></div>

            <div class="flex flex-col items-center justify-center p-10 text-center">
                <div class="mb-8 p-4 bg-neutral-50 dark:bg-neutral-800 rounded-full shadow-inner">
                    @php $logo = \App\Models\Preferencia::first()?->logo; @endphp
                    @if($logo)
                        <img src="{{ asset('storage/' . $logo) }}" alt="Logo" class="w-20 h-20 object-contain grayscale opacity-50">
                    @else
                        <div class="w-20 h-20 flex items-center justify-center text-3xl font-bold text-neutral-300">2S3</div>
                    @endif
                </div>

                {{ $slot }}

                <a href="{{ route('dashboard') }}" 
                   class="inline-flex items-center px-8 py-3 text-base font-bold text-white bg-neutral-800 hover:bg-black dark:bg-orange-500 dark:hover:bg-orange-600 rounded-xl transition-all shadow-lg hover:scale-105 active:scale-95">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Volver al inicio
                </a>
            </div>
        </div>
        <p class="mt-8 text-center text-sm text-neutral-400 font-medium">
            &copy; {{ date('Y') }} — Sistema de Gestión 2sin3
        </p>
    </div>
</body>
</html>