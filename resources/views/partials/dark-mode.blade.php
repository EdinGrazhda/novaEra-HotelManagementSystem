<!-- Dark Mode Support -->
<link href="{{ asset('css/dark-mode.css') }}" rel="stylesheet">
<script src="{{ asset('js/dark-mode.js') }}" defer></script>

<!-- Page Specific Dark Mode Styles -->
@if(request()->is('rooms*'))
    <link href="{{ asset('css/room-dark-mode.css') }}" rel="stylesheet">
@endif

@if(request()->is('menu*') && !request()->is('menuService*'))
    <link href="{{ asset('css/menu-dark-mode.css') }}" rel="stylesheet">
@endif

@if(request()->is('menuService*'))
    <link href="{{ asset('css/menu-dark-mode.css') }}" rel="stylesheet">
@endif

@if(request()->is('cleaning*'))
    <link href="{{ asset('css/cleaning-dark-mode.css') }}" rel="stylesheet">
@endif

@if(request()->is('calendar*'))
    <link href="{{ asset('css/calendar-dark-mode.css') }}" rel="stylesheet">
@endif

@if(request()->is('roles*') || request()->is('permissions*') || request()->is('users/*/roles'))
    <link href="{{ asset('css/roles-dark-mode.css') }}" rel="stylesheet">
@endif
