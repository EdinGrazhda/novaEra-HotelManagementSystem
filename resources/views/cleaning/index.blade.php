<x-layouts.app :title="__('Cleaning Room')">
<!-- Include custom CSS for cleaning -->
<link href="{{ asset('css/cleaning.css') }}" rel="stylesheet">
<link href="{{ asset('css/rooms.css') }}" rel="stylesheet">
<link href="{{ asset('css/room-overrides.css') }}" rel="stylesheet">
<link href="{{ asset('css/room-card-fixes.css') }}" rel="stylesheet">
<link href="{{ asset('css/room-status-colors.css') }}" rel="stylesheet">
<link href="{{ asset('css/cleaning-colors.css') }}" rel="stylesheet">
<link href="{{ asset('css/room-dark-mode.css') }}" rel="stylesheet">
<link href="{{ asset('css/cleaning-dark-mode.css') }}" rel="stylesheet">
<!-- Add CSRF Token meta tag -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container mx-auto px-4 py-8">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-[#1B1B18] dark:text-white">Cleaning Management</h1>
    </div>
    
    @if(session('success'))
        <div id="success-alert" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <div class="flex justify-between items-center">
                <p>{{ session('success') }}</p>
                <span class="text-green-700 hover:text-green-800 cursor-pointer" onclick="document.getElementById('success-alert').remove()">×</span>
            </div>
        </div>

        <script>
            // Auto-hide the success message after 5 seconds
            setTimeout(function() {
                const alert = document.getElementById('success-alert');
                if (alert) {
                    alert.classList.add('opacity-0', 'transition-opacity', 'duration-500');
                    setTimeout(() => alert.remove(), 500);
                }
            }, 1500);
        </script>
    @endif
    
    @if(session('error'))
        <div id="error-alert" class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
            <div class="flex justify-between items-center">
                <p>{{ session('error') }}</p>
                <span class="text-red-700 hover:text-red-800 cursor-pointer" onclick="document.getElementById('error-alert').remove()">×</span>
            </div>
        </div>

        <script>
            // Auto-hide the error message after 5 seconds
            setTimeout(function() {
                const alert = document.getElementById('error-alert');
                if (alert) {
                    alert.classList.add('opacity-0', 'transition-opacity', 'duration-500');
                    setTimeout(() => alert.remove(), 500);
                }
            }, 1500);
        </script>
    @endif
    
    <!-- Cleaning management using Livewire component with real-time updates -->
    <livewire:cleaning-status-list 
        :cleaningFilter="$cleaningFilter" 
        :searchQuery="$searchQuery ?? ''" 
    />
    
    <!-- Add active filter styling -->
    <style>
        .active-filter {
            font-weight: bold;
            box-shadow: 0 0 0 2px rgba(0, 0, 0, 0.2);
            transform: scale(1.05);
        }
    </style>
    
    <script>
        // Listen for the urlChanged event from Livewire
        document.addEventListener('livewire:init', () => {
            Livewire.on('urlChanged', (params) => {
                let url = new URL(window.location);
                
                // Update URL parameters
                Object.keys(params).forEach(key => {
                    if (params[key] === null || params[key] === '') {
                        url.searchParams.delete(key);
                    } else {
                        url.searchParams.set(key, params[key]);
                    }
                });
                
                // Update the URL without refreshing the page
                window.history.pushState({}, '', url);
            });
            
            // Listen for status updates and notify any open dashboard
            Livewire.on('statusUpdated', (data) => {
                console.log('Status updated event received:', data);
                
                // Try to notify dashboard if it's open in another window/tab
                try {
                    // Broadcast to all windows
                    if (typeof BroadcastChannel !== 'undefined') {
                        const bc = new BroadcastChannel('nova-era-dashboard');
                        bc.postMessage({
                            type: 'refresh-dashboard',
                            data: data
                        });
                    }
                    
                    // Also create a custom event for any dashboard in the current page
                    const event = new CustomEvent('refresh-dashboard', {
                        detail: data,
                        bubbles: true
                    });
                    document.dispatchEvent(event);
                } catch (e) {
                    console.error('Failed to notify dashboard:', e);
                }
            });
        });
    </script>
</div>
</x-layouts.app>
