<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'NovaERA HMS') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --yellow: #F8B803;
            --white: #FFFFFF;
            --soft-black: #1B1B18;
            --gray: #706F6C;
            --light-gray: #F5F5F5;
        }

        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="font-sans antialiased bg-[#F5F5F5]">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm">
            <div class="container mx-auto px-4 py-3">
                <div class="flex justify-between items-center">
                    <a href="{{ route('home') }}" class="text-xl font-bold text-[#1B1B18]">
                        NovaERA HMS
                    </a>
                    <div class="flex items-center space-x-4">
                        @auth
                            <a href="{{ route('dashboard') }}" class="text-[#706F6C] hover:text-[#F8B803]">Dashboard</a>
                            <a href="{{ route('rooms.index') }}" class="text-[#706F6C] hover:text-[#F8B803]">Rooms</a>
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="flex items-center text-[#706F6C] hover:text-[#F8B803]">
                                    {{ Auth::user()->name }}
                                    <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                    <a href="{{ route('settings.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Settings</a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="text-[#706F6C] hover:text-[#F8B803]">Login</a>
                            <a href="{{ route('register') }}" class="bg-[#F8B803] text-[#1B1B18] px-4 py-2 rounded-md hover:bg-yellow-500">Register</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main>
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-white py-6 mt-12">
            <div class="container mx-auto px-4">
                <div class="text-center text-gray-500">
                    <p>&copy; {{ date('Y') }} NovaERA HMS. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>

    <!-- Alpine.js -->
    {{-- <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script> --}}
</body>
</html>
