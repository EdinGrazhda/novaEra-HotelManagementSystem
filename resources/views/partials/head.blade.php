<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>{{ $title ?? config('app.name') }}</title>

<!-- Immediate Light Mode Setup - Runs before page renders -->
<script>
    // Force light mode immediately before page renders
    document.documentElement.classList.remove('dark');
    
    // Set theme in localStorage to maintain consistency
    localStorage.setItem('theme', 'light');
</script>

<link rel="icon" href="/favicon.ico" sizes="any">
<link rel="icon" href="/favicon.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

@vite(['resources/css/app.css', 'resources/js/app.js'])
<x-light-mode-appearance />

<!-- Dark Mode Support for all sidebar pages -->
{{-- @include('partials.dark-mode') --}}
