<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @persist('favicon')
        <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    @endpersist
    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen lg:flex">
        <div
            class="hidden lg:flex w-1/2 items-center justify-center bg-gradient-to-br from-blue-600 to-blue-800 p-12 text-white">
            <div class="max-w-md text-center">
                <div class="bg-white/20 backdrop-blur-sm p-4 rounded-xl shadow-lg inline-block mb-6">
                    <img src="{{ asset('images/logo1.jpg') }}" alt="AnJi NYK Logo"
                        class="w-40 h-auto mix-blend-normal rounded-lg">
                </div>
                <h1 class="text-5xl font-bold tracking-wider">WELCOME</h1>
                <p class="mt-4 text-lg text-blue-100">
                    Sign in to access the Asset Management dashboard.
                </p>
            </div>
        </div>
        <div class="w-full lg:w-1/2 flex items-center justify-center bg-gray-100 p-6">
            <div class="w-full max-w-md">
                <div class="lg:hidden text-center mb-6">
                    <img src="{{ asset('images/logo1.jpg') }}" alt="ANJI-NYK Logo" class="w-32 h-auto mx-auto">
                </div>
                <div class="bg-white p-8 rounded-2xl shadow-lg">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
    @livewireScripts
</body>

</html>
