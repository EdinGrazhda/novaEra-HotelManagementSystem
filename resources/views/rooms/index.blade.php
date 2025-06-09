<x-layouts.app :title="__('Rooms')">
<!-- Handle filter form submission -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle form submission and preserve other filters
        const form = document.querySelector('.room-filter-form');
        const searchInput = form.querySelector('input[name="search"]');
        
        // Handle filter button clicks 
        document.querySelectorAll('button[name="status_filter"], button[name="cleaning_filter"]').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Get the type and value
                const type = this.getAttribute('name');
                const value = this.getAttribute('value');
                
                // Create or update form parameters
                const formData = new FormData(form);
                formData.set(type, value);
                
                // Build the URL with all parameters
                let url = form.action + '?';
                for (const [key, val] of formData.entries()) {
                    if (val) {
                        url += encodeURIComponent(key) + '=' + encodeURIComponent(val) + '&';
                    }
                }
                
                // Navigate to the filtered URL
                window.location.href = url.slice(0, -1);  // Remove trailing &
            });
        });
        
        // Handle search submit to preserve selected filters
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Build URL with all current parameters
            const formData = new FormData(form);
            let url = form.action + '?';
            
            for (const [key, val] of formData.entries()) {
                if (val) {
                    url += encodeURIComponent(key) + '=' + encodeURIComponent(val) + '&';
                }
            }
            
            // Navigate to the filtered URL
            window.location.href = url.slice(0, -1);  // Remove trailing &
        });
    });
</script>
<!-- Include custom CSS for rooms -->            
<link href="{{ asset('css/rooms.css') }}" rel="stylesheet">
<link href="{{ asset('css/room-overrides.css') }}" rel="stylesheet">
<link href="{{ asset('css/room-card-fixes.css') }}" rel="stylesheet">
<link href="{{ asset('css/room-status-colors.css') }}" rel="stylesheet">
<link href="{{ asset('css/room-status-only.css') }}" rel="stylesheet">

<div class="container mx-auto px-4 py-8">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-[#1B1B18]">Room Management</h1>
        <a href="{{ route('rooms.create') }}" class="px-4 py-2 bg-[#F8B803] text-[#1B1B18] font-medium rounded-md hover:bg-yellow-500 transition duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Add New Room
        </a>
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
    @endif    <!-- Room filter controls with Laravel -->
    <div class="mb-6 bg-white rounded-lg shadow-sm p-4">
        <form action="{{ route('rooms.index') }}" method="GET" class="room-filter-form">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex flex-wrap items-center gap-4">
                    <div class="font-semibold">Filter by status:</div>
                    <div class="flex gap-3">
                        <button type="submit" name="status_filter" value="all" 
                                class="px-3 py-1 rounded-full bg-gray-200 text-gray-700 text-sm hover:bg-gray-300 
                                {{ $statusFilter === 'all' ? 'active-filter' : '' }}">
                            All
                        </button>
                        <button type="submit" name="status_filter" value="available" 
                                class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm hover:bg-green-200 
                                {{ $statusFilter === 'available' ? 'active-filter' : '' }}">
                            Available
                        </button>
                        <button type="submit" name="status_filter" value="occupied" 
                                class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-sm hover:bg-red-200 
                                {{ $statusFilter === 'occupied' ? 'active-filter' : '' }}">
                            Occupied
                        </button>
                        <button type="submit" name="status_filter" value="maintenance" 
                                class="px-3 py-1 rounded-full bg-gray-100 text-gray-700 text-sm hover:bg-gray-300 
                                {{ $statusFilter === 'maintenance' ? 'active-filter' : '' }}">
                            Maintenance
                        </button>
                    </div>
                </div>
                
                <div class="flex flex-wrap items-center gap-4 mt-3">
                    <div class="font-semibold">Filter by cleaning:</div>
                    <div class="flex gap-3">
                        <button type="submit" name="cleaning_filter" value="all" 
                                class="px-3 py-1 rounded-full bg-gray-200 text-gray-700 text-sm hover:bg-gray-300 
                                {{ $cleaningFilter === 'all' ? 'active-filter' : '' }}">
                            All
                        </button>
                        <button type="submit" name="cleaning_filter" value="clean" 
                                class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm hover:bg-green-200 
                                {{ $cleaningFilter === 'clean' ? 'active-filter' : '' }}">
                            Clean
                        </button>
                        <button type="submit" name="cleaning_filter" value="not_cleaned" 
                                class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-sm hover:bg-red-200 
                                {{ $cleaningFilter === 'not_cleaned' ? 'active-filter' : '' }}">
                            Not Cleaned
                        </button>
                        <button type="submit" name="cleaning_filter" value="in_progress" 
                                class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-sm hover:bg-yellow-200 
                                {{ $cleaningFilter === 'in_progress' ? 'active-filter' : '' }}">
                            In Progress
                        </button>
                    </div>
                </div>
                
                <!-- Search Bar -->
                <div class="relative w-72">
                    <input type="text" name="search" value="{{ $searchQuery ?? '' }}" placeholder="Search by room #, category, floor..." 
                        class="w-full px-4 py-2 pl-10 pr-10 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#F8B803]">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    
                    @if(!empty($searchQuery))
                        <a href="{{ route('rooms.index', ['status_filter' => $statusFilter, 'cleaning_filter' => $cleaningFilter]) }}" 
                           class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @endif
                </div>
            </div>
            
            <!-- Add hidden inputs to preserve other filters when one button is clicked -->
            <input type="hidden" id="active-status-filter" name="status_filter" value="{{ $statusFilter }}">
            <input type="hidden" id="active-cleaning-filter" name="cleaning_filter" value="{{ $cleaningFilter }}">
        </form>
    </div>
    
        <!-- Room grid display -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($rooms as $room)
            <div class="room-box {{ $room->room_status }} border-t-4 rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-all relative" 
                data-status="{{ $room->room_status }}"
                data-cleaning="{{ $room->cleaning_status ?? 'clean' }}"
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
                
                <!-- Cleaning status badges removed from top of card -->
                
                <div class="p-4">
                    <div class="flex justify-between items-center">
                        <h3 class="room-number text-xl font-bold">{{ $room->room_number }}</h3>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            {{ $room->room_type == 'single' ? 'bg-blue-100 text-blue-800' : 
                            ($room->room_type == 'double' ? 'bg-purple-100 text-purple-800' : 'bg-yellow-100 text-yellow-800') }}">
                            {{ ucfirst($room->room_type) }}
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
                            <span>Status</span>
                            <span class="font-medium room-status-badge
                                {{ $room->room_status == 'available' ? 'bg-green-100 text-green-800' : 
                                ($room->room_status == 'occupied' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800') }}">
                                {{ ucfirst($room->room_status) }}
                            </span>
                        </div>
                        
                        <div class="room-info-item">
                            <span>Cleaning</span>
                            <span class="font-medium room-cleaning-badge {{ $room->cleaning_status ?? 'clean' }}" 
                                data-cleaning-status="{{ $room->cleaning_status ?? 'clean' }}">
                                {{ ucfirst(str_replace('_', ' ', $room->cleaning_status ?? 'clean')) }}
                            </span>
                        </div>
                        
                        <!-- Combined Check-in/Check-out status -->
                        <div class="room-info-item">
                            <span>Check Status</span>
                            <span class="font-medium">
                                <span class="{{ $room->checkin_status == 'checked_in' ? 'text-green-600' : 'text-gray-600' }} mr-1">
                                    <i class="fas fa-sign-in-alt"></i> {{ $room->checkin_status == 'checked_in' ? 'In' : 'Not In' }}
                                </span>
                                /
                                <span class="{{ $room->checkout_status == 'checked_out' ? 'text-blue-600' : 'text-gray-600' }} ml-1">
                                    <i class="fas fa-sign-out-alt"></i> {{ $room->checkout_status == 'checked_out' ? 'Out' : 'Not Out' }}
                                </span>
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white bg-opacity-50 px-4 py-3 border-t border-gray-200">
                    <!-- Action buttons -->
                    <div class="flex justify-between items-center">
                        <div class="flex space-x-3">
                            <a href="{{ route('rooms.show', $room) }}" class="text-indigo-600 hover:text-indigo-900" title="View">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                </svg>
                            </a>
                            <a href="{{ route('rooms.edit', $room) }}" class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                </svg>
                            </a>
                            <form action="{{ route('rooms.destroy', $room) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" 
                                    onclick="return confirm('Are you sure you want to delete this room?')" title="Delete">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                        
                        <!-- Room badge icon -->
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 {{ 
                                $room->room_status == 'available' ? 'text-green-500' : 
                                ($room->room_status == 'occupied' ? 'text-red-500' : 'text-gray-500') 
                            }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
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
                <a href="{{ route('rooms.create') }}" class="mt-4 inline-block px-4 py-2 bg-[#F8B803] text-[#1B1B18] font-medium rounded-md hover:bg-yellow-500 transition duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Add a new room
                </a>
            </div>
        @endforelse
    </div>
    
    <!-- Add active filter styling -->
    <style>
        .active-filter {
            font-weight: bold;
            box-shadow: 0 0 0 2px rgba(0, 0, 0, 0.2);
            transform: scale(1.05);
        }
    </style>
</div>
</x-layouts.app>