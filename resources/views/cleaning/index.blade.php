<x-layouts.app :title="__('Cleaning Room')">
<!-- Include custom CSS for cleaning -->
<link href="{{ asset('css/cleaning.css') }}" rel="stylesheet">
<!-- Add CSRF Token meta tag -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container mx-auto px-4 py-8">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-[#1B1B18]">Cleaning Management</h1>
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
    
    <!-- Cleaning filter controls -->
    <div class="mb-6 bg-white rounded-lg shadow-sm p-4">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex flex-wrap items-center gap-4">
                <div class="font-semibold">Filter by cleaning status:</div>
                <div class="flex gap-3">
                    <button data-filter="all" class="filter-btn active-filter px-3 py-1 rounded-full bg-gray-200 text-gray-700 text-sm hover:bg-gray-300">All</button>
                    <button data-filter="clean" class="filter-btn px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm hover:bg-green-200">Clean</button>
                    <button data-filter="not_cleaned" class="filter-btn px-3 py-1 rounded-full bg-red-100 text-red-700 text-sm hover:bg-red-200">Not Cleaned</button>
                    <button data-filter="in_progress" class="filter-btn px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-sm hover:bg-yellow-200">In Progress</button>
                </div>
            </div>
            
            <!-- Search Bar -->
            <div class="relative w-72">
                <input type="text" id="room-search" placeholder="Search by room #, floor..." 
                    class="w-full px-4 py-2 pl-10 pr-10 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#F8B803]">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <button id="clear-search" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600" style="display: none;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Room grid display for cleaning -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($cleaning as $room)            <div class="room-box border-t-4 rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-all" 
                data-status="{{ $room->cleaning_status }}"
                data-room="{{ $room->id }}"
                style="{{ $room->cleaning_status == 'clean' ? 'border-color: #10B981; background-color: #ECFDF5' : 
                    ($room->cleaning_status == 'not_cleaned' ? 'border-color: #EF4444; background-color: #FEF2F2' : 
                    'border-color: #F59E0B; background-color: #FEF3C7') }}">
                
                <div class="p-4">
                    <div class="flex justify-between items-center">
                        <h3 class="room-number text-xl font-bold">{{ $room->room_number }}</h3>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            {{ $room->room_status == 'available' ? 'bg-green-100 text-green-800' : 
                               ($room->room_status == 'occupied' ? 'bg-red-100 text-red-800' : 
                               'bg-gray-100 text-gray-800') }}">
                            {{ ucfirst($room->room_status) }}
                        </span>
                    </div>
                    
                    <div class="room-info mt-3">
                        <div class="room-info-item">
                            <span>Floor</span>
                            <span class="font-medium">{{ $room->room_floor }}</span>
                        </div>
                        <div class="room-info-item">
                            <span>Category</span>
                            <span class="font-medium">{{ $room->roomCategory->category_name ?? 'N/A' }}</span>
                        </div>
                        <div class="room-info-item">
                            <span>Cleaning Status</span>
                            <span class="font-medium room-status-badge
                                {{ $room->cleaning_status == 'clean' ? 'bg-green-100 text-green-800' : 
                                   ($room->cleaning_status == 'not_cleaned' ? 'bg-red-100 text-red-800' : 
                                   'bg-yellow-100 text-yellow-800') }}">
                                {{ ucfirst(str_replace('_', ' ', $room->cleaning_status)) }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white bg-opacity-50 px-4 py-3 border-t border-gray-200">
                    <!-- Cleaning status action buttons -->
                    <div class="flex flex-col gap-2">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-600">Change Cleaning Status:</span>
                        </div>
                        <div class="flex justify-between gap-2">
                            <button class="clean-status-btn flex-1 px-2 py-1 text-xs bg-green-100 text-green-800 rounded hover:bg-green-200 {{ $room->cleaning_status == 'clean' ? 'border-2 border-green-500' : '' }}" 
                                    data-room="{{ $room->id }}" 
                                    data-status="clean">
                                Clean
                            </button>
                            <button class="clean-status-btn flex-1 px-2 py-1 text-xs bg-red-100 text-red-800 rounded hover:bg-red-200 {{ $room->cleaning_status == 'not_cleaned' ? 'border-2 border-red-500' : '' }}" 
                                    data-room="{{ $room->id }}" 
                                    data-status="not_cleaned">
                                Not Cleaned
                            </button>
                            <button class="clean-status-btn flex-1 px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded hover:bg-yellow-200 {{ $room->cleaning_status == 'in_progress' ? 'border-2 border-yellow-500' : '' }}" 
                                    data-room="{{ $room->id }}" 
                                    data-status="in_progress">
                                In Progress
                            </button>
                        </div>
                        <div class="mt-2">
                            <input type="text" placeholder="Add cleaning notes..." 
                                   class="cleaning-notes w-full px-3 py-1 text-xs border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#F8B803]"
                                   data-room="{{ $room->id }}" 
                                   value="{{ $room->cleaning_notes }}">
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white p-8 rounded-lg shadow text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <p class="mt-4 text-xl text-gray-600">No rooms found.</p>
            </div>
        @endforelse
    </div>
      <!-- Include the cleaning.js script -->
    <script src="{{ asset('js/cleaning.js') }}"></script>
    
    <script>
        // Debug info
        console.log('Cleaning page loaded');
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM fully loaded');
            // Check if CSRF token is available
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (csrfToken) {
                console.log('CSRF token available: ' + csrfToken.getAttribute('content').substring(0, 10) + '...');
            } else {
                console.error('CSRF token not found!');
            }
            
            // Log all room boxes
            const roomBoxes = document.querySelectorAll('.room-box');
            console.log(`Found ${roomBoxes.length} room boxes`);
            
            // Log all cleaning status buttons
            const statusButtons = document.querySelectorAll('.clean-status-btn');
            console.log(`Found ${statusButtons.length} status buttons`);
            
            // Log all notes inputs
            const notesInputs = document.querySelectorAll('.cleaning-notes');
            console.log(`Found ${notesInputs.length} notes inputs`);
        });
    </script>
</div>
</x-layouts.app>