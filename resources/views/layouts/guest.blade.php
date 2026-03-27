<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">

    <div class="min-h-screen relative bg-gradient-to-br from-blue-500 to-blue-800">
        <div class="fixed top-5 right-5 z-50 space-y-2">

            <div class="fixed top-5 right-5 z-50 space-y-2">

                @if (session('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                        x-transition:enter="transform ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transform ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 translate-y-2"
                        class="bg-green-500 text-white px-4 py-3 rounded-lg shadow-lg flex items-center gap-2">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif
            </div>

        </div>

        <div class="absolute inset-0 bg-black/10"></div>

        <div class="relative z-10 min-h-screen flex items-center justify-center md:justify-end px-4 md:px-0">

            <div class="hidden md:block absolute left-20 text-white max-w-md">
                <h1 class="text-4xl font-bold mb-4">Vehicle Management System</h1>
                <p class="text-blue-100">
                    Monitor kendaraan, approval, dan penggunaan secara realtime.
                </p>
            </div>

            <div
                class="hidden md:block absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 h-64 w-px bg-white/40">
            </div>

            <div class="w-full max-w-md md:mr-10">
                {{ $slot }}
            </div>

        </div>
    </div>

</body>

</html>
