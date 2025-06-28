<x-layouts.app :title="__('Menu Service')">
    <!-- Include custom CSS -->
    <link href="{{ asset('css/room-overrides.css') }}" rel="stylesheet">
    <link href="{{ asset('css/room-card-fixes.css') }}" rel="stylesheet">
    <link href="{{ asset('css/room-status-colors.css') }}" rel="stylesheet">
    
    <!-- Add CSRF Token meta tag -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @livewire('real-time-menu-service')
    
</x-layouts.app>
