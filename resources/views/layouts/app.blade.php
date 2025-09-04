<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <div x-data="{ sidebarOpen: false }" class="relative min-h-screen md:flex bg-gray-100">
            
            <!-- Sidebar -->
            @include('layouts.sidebar')

            <!-- Main Content -->
            <div class="flex-1 flex flex-col md:pl-64"> <!-- ▼▼▼ แก้ไขบรรทัดนี้ ▼▼▼ -->
                
                <!-- Header -->
                @if (isset($header))
                    <header class="bg-white shadow">
                        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                            <div class="flex justify-between items-center">
                                
                                <!-- Hamburger Menu for mobile -->
                                <div class="md:hidden">
                                    <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-gray-600 focus:outline-none">
                                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                        </svg>
                                    </button>
                                </div>
                                
                                <!-- Header Title -->
                                <div class="flex-1 min-w-0 md:text-left text-center">
                                    {{ $header }}
                                </div>

                                <!-- User Menu -->
                                <div class="flex items-center space-x-6">
                                    @if(auth()->user()->role == 'admin')
                                        <div>
                                            <livewire:notification-bell />
                                        </div>
                                    @endif
                                    <div x-data="{ open: false }" class="relative">
                                        <button @click="open = !open" class="flex items-center space-x-2 text-sm font-medium text-gray-500 hover:text-gray-700">
                                            <span>{{ Auth::user()->name }}</span>
                                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                        </button>
                                        <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-20" style="display: none;">
                                            <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">My Profile</a>
                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf
                                                <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                    Log Out
                                                </a>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </header>
                @endif

                <!-- Page Content -->
                <main class="flex-grow">
                    {{ $slot }}
                </main>

            </div>
        </div>

        @livewireScripts
        @stack('scripts')
    </body>
</html>

