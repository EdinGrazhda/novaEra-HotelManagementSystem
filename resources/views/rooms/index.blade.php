<x-layouts.app :title="__('Rooms')">
<!-- Include custom CSS for rooms -->            
<link href="{{ asset('css/rooms.css') }}" rel="stylesheet">
<link href="{{ asset('css/room-overrides.css') }}" rel="stylesheet">
<link href="{{ asset('css/room-card-fixes.css') }}" rel="stylesheet">
<link href="{{ asset('css/room-status-colors.css') }}" rel="stylesheet">
<link href="{{ asset('css/room-status-only.css') }}" rel="stylesheet">

<div class="container mx-auto px-4 py-8">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-[#1B1B18]">Room Management</h1>
        <div class="flex space-x-3">
            <a href="{{ route('rooms.updateStatuses') }}" class="px-4 py-2 bg-blue-500 text-white font-medium rounded-md hover:bg-blue-600 transition duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                </svg>
                Update Room Statuses
            </a>
            <a href="{{ route('rooms.create') }}" class="px-4 py-2 bg-[#F8B803] text-[#1B1B18] font-medium rounded-md hover:bg-yellow-500 transition duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Add New Room
            </a>
        </div>
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
            }, 3000);
        </script>
    @endif
    
    <!-- Room management using Livewire component with real-time updates -->
    <livewire:room-status-list 
        :statusFilter="$statusFilter" 
        :cleaningFilter="$cleaningFilter" 
        :searchQuery="$searchQuery ?? ''" 
    />
    
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