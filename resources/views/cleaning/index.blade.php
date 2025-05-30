<x-layouts.app :title="__('Cleaning Room')">
<!-- Include custom CSS for cleaning -->
<link href="{{ asset('css/cleaning.css') }}" rel="stylesheet">
<link href="{{ asset('css/room-overrides.css') }}" rel="stylesheet">
<link href="{{ asset('css/room-card-fixes.css') }}" rel="stylesheet">
<link href="{{ asset('css/room-status-colors.css') }}" rel="stylesheet">
<link href="{{ asset('css/cleaning-colors.css') }}" rel="stylesheet">
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
        @forelse($cleaning as $room)
            <div class="room-box border-t-4 rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-all" 
                data-status="{{ $room->room_status }}"
                data-cleaning="{{ $room->cleaning_status }}"
                data-room="{{ $room->id }}"
                data-floor="{{ $room->room_floor }}"
                style="
                    @if($room->room_status == 'available')
                        border-color: #10B981 !important; background-color: #ECFDF5 !important;
                    @elseif($room->room_status == 'occupied') 
                        border-color: #EF4444 !important; background-color: #FEF2F2 !important;
                    @else 
                        border-color: #6B7280 !important; background-color: #F9FAFB !important;
                    @endif
                ">
                
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
                            <span class="font-medium room-status-badge {{ $room->cleaning_status }}"
                                data-cleaning-status="{{ $room->cleaning_status }}">
                                {{ ucfirst(str_replace('_', ' ', $room->cleaning_status)) }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white bg-opacity-50 px-4 py-3 border-t border-gray-200">
                    <!-- Cleaning status action buttons using Laravel forms -->
                    <div class="flex flex-col gap-2">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-600">Change Cleaning Status:</span>
                        </div>
                        
                        <div class="grid grid-cols-3 gap-2">
                            <form action="{{ route('cleaning.updateStatus', $room->id) }}" method="POST" class="flex-1">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="cleaning_status" value="clean">
                                <input type="hidden" name="cleaning_notes" value="{{ $room->cleaning_notes }}">
                                <button type="submit" class="w-full h-8 px-2 py-1 text-xs bg-green-100 text-green-800 rounded hover:bg-green-200 flex items-center justify-center {{ $room->cleaning_status == 'clean' ? 'border-2 border-green-500' : '' }}">
                                    Clean
                                </button>
                            </form>
                            
                            <form action="{{ route('cleaning.updateStatus', $room->id) }}" method="POST" class="flex-1">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="cleaning_status" value="not_cleaned">
                                <input type="hidden" name="cleaning_notes" value="{{ $room->cleaning_notes }}">
                                <button type="submit" class="w-full h-8 px-2 py-1 text-xs bg-red-100 text-red-800 rounded hover:bg-red-200 flex items-center justify-center {{ $room->cleaning_status == 'not_cleaned' ? 'border-2 border-red-500' : '' }}">
                                    Not Cleaned
                                </button>
                            </form>
                            
                            <form action="{{ route('cleaning.updateStatus', $room->id) }}" method="POST" class="flex-1">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="cleaning_status" value="in_progress">
                                <input type="hidden" name="cleaning_notes" value="{{ $room->cleaning_notes }}">
                                <button type="submit" class="w-full h-8 px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded hover:bg-yellow-200 flex items-center justify-center {{ $room->cleaning_status == 'in_progress' ? 'border-2 border-yellow-500' : '' }}">
                                    In Progress
                                </button>
                            </form>
                        </div>
                        
                        <!-- Cleaning notes form -->
                        <div class="mt-2">
                            <form action="{{ route('cleaning.updateStatus', $room->id) }}" method="POST" class="flex">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="cleaning_status" value="{{ $room->cleaning_status }}">
                                <input type="text" name="cleaning_notes" placeholder="Add cleaning notes..." 
                                       class="w-full px-3 py-1 text-xs border border-gray-300 rounded-l-md focus:outline-none focus:ring-1 focus:ring-[#F8B803]"
                                       value="{{ $room->cleaning_notes }}">
                                <button type="submit" class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-r-md hover:bg-blue-200 border border-blue-300">
                                    Save
                                </button>
                            </form>
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
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get all necessary elements
            const searchInput = document.getElementById('room-search');
            const clearButton = document.getElementById('clear-search');
            const roomBoxes = document.querySelectorAll('.room-box');
            const filterButtons = document.querySelectorAll('.filter-btn');
            
            let currentFilter = 'all'; // Track current filter state
            
            // Debug log for room boxes
            console.log("Room boxes and their cleaning statuses:");
            roomBoxes.forEach(room => {
                const roomNumber = room.querySelector('.room-number').textContent;
                const cleaningStatus = room.getAttribute('data-cleaning');
                console.log(`Room ${roomNumber}: ${cleaningStatus}`);
            });
            
            // Search functionality
            function filterRoomsBySearch() {
                const searchTerm = searchInput.value.toLowerCase();
                
                roomBoxes.forEach(room => {
                    const roomNumber = room.querySelector('.room-number').textContent.toLowerCase();
                    const roomFloor = room.getAttribute('data-floor') || '';
                    const shouldShowByFilter = currentFilter === 'all' || room.getAttribute('data-cleaning') === currentFilter;
                    
                    // Only apply search filter to rooms that pass the cleaning status filter
                    if (shouldShowByFilter) {
                        if (roomNumber.includes(searchTerm) || roomFloor.toLowerCase().includes(searchTerm)) {
                            room.style.display = '';
                        } else {
                            room.style.display = 'none';
                        }
                    }
                });
            }
            
            // Filter functionality by cleaning status
            function filterRoomsByStatus(filter) {
                console.log(`Filtering rooms by status: ${filter}`);
                currentFilter = filter;
                
                roomBoxes.forEach(room => {
                    const cleaningStatus = room.getAttribute('data-cleaning');
                    console.log(`Room ${room.querySelector('.room-number').textContent}: status=${cleaningStatus}, filter=${filter}`);
                    
                    if (filter === 'all' || cleaningStatus === filter) {
                        room.style.display = '';
                    } else {
                        room.style.display = 'none';
                    }
                });
                
                // Re-apply search filter on top of cleaning status filter
                if (searchInput && searchInput.value) {
                    filterRoomsBySearch();
                }
            }
            
            // Set up search event listener
            if (searchInput) {
                searchInput.addEventListener('input', filterRoomsBySearch);
                
                // Show/hide clear button based on search input
                searchInput.addEventListener('input', function() {
                    if (this.value.length > 0 && clearButton) {
                        clearButton.style.display = 'flex';
                    } else if (clearButton) {
                        clearButton.style.display = 'none';
                    }
                });
            }
            
            // Set up clear button functionality
            if (clearButton) {
                clearButton.addEventListener('click', function() {
                    if (searchInput) {
                        searchInput.value = '';
                        clearButton.style.display = 'none';
                        filterRoomsByStatus(currentFilter); // Reset to current filter state
                    }
                });
            }
            
            // Set up filter button event listeners
            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const filter = this.getAttribute('data-filter');
                    
                    // Update active button
                    filterButtons.forEach(btn => btn.classList.remove('active-filter'));
                    this.classList.add('active-filter');
                    
                    // Apply the filter
                    filterRoomsByStatus(filter);
                });
            });
        });
    </script>
    
    <!-- Include room color manager only -->
    <script src="{{ asset('js/room-color-manager.js') }}"></script>
</div>
</x-layouts.app>
