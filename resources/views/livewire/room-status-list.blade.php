<div wire:poll.3s.keep-alive wire:loading.class="opacity-95">
    <style>
        /* Disable loading overlay and make transitions smooth */
        [wire\:loading], [wire\:loading\.delay], [wire\:loading\.inline-block], [wire\:loading\.inline], [wire\:loading\.block], [wire\:loading\.flex], [wire\:loading\.table], [wire\:loading\.grid], [wire\:loading\.inline-flex] {
            display: none;
        }
        
        /* Add smooth transition */
        .transition-all-smooth {
            transition: all 0.2s ease-in-out;
        }
        
        /* Filter button styles */
        .filter-button {
            border-radius: 0.375rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
            white-space: nowrap;
        }
        
        .filter-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 10%), 0 2px 4px -1px rgb(0 0 0 / 6%);
        }
        
        .filter-button.active {
            font-weight: 600;
            box-shadow: 0 0 0 2px rgba(0, 0, 0, 0.1);
            transform: scale(1.03);
        }
    </style>
    
    <script>
        // Configure Livewire for seamless updates
        document.addEventListener('livewire:init', () => {
            Livewire.hook('request', ({ fail }) => {
                fail(({ status, content }) => {
                    // Silently fail to prevent errors from showing
                    console.log('Silent fail for seamless UX');
                    return false;
                });
            });
        });
    </script>
    
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
    
    <!-- Filter section -->
    <div class="mb-6 bg-white rounded-lg shadow-md p-4 border border-gray-200">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
            <!-- Room Status Filter -->
            <div class="flex-1">
                <h3 class="text-sm font-medium text-gray-700 mb-2">Room Status Filter:</h3>
                <div class="flex flex-wrap gap-1">
                    <button wire:click="$set('statusFilter', 'all')" 
                            class="filter-button text-xs py-1 px-2 bg-gray-100 text-gray-800 hover:bg-gray-200 {{ $statusFilter === 'all' ? 'active' : '' }}">
                        All
                    </button>
                    <button wire:click="$set('statusFilter', 'available')" 
                            class="filter-button text-xs py-1 px-2 bg-green-100 text-green-800 hover:bg-green-200 {{ $statusFilter === 'available' ? 'active' : '' }}">
                        Available
                    </button>
                    <button wire:click="$set('statusFilter', 'occupied')" 
                            class="filter-button text-xs py-1 px-2 bg-red-100 text-red-800 hover:bg-red-200 {{ $statusFilter === 'occupied' ? 'active' : '' }}">
                        Occupied
                    </button>
                    <button wire:click="$set('statusFilter', 'maintenance')" 
                            class="filter-button text-xs py-1 px-2 bg-gray-100 text-gray-800 hover:bg-gray-200 {{ $statusFilter === 'maintenance' ? 'active' : '' }}">
                        Maintenance
                    </button>
                </div>
            </div>
            
            <!-- Cleaning Status Filter -->
            <div class="flex-1">
                <h3 class="text-sm font-medium text-gray-700 mb-2">Cleaning Status Filter:</h3>
                <div class="flex flex-wrap gap-1">
                    <button wire:click="$set('cleaningFilter', 'all')" 
                            class="filter-button text-xs py-1 px-2 bg-gray-100 text-gray-800 hover:bg-gray-200 {{ $cleaningFilter === 'all' ? 'active' : '' }}">
                        All
                    </button>
                    <button wire:click="$set('cleaningFilter', 'clean')" 
                            class="filter-button text-xs py-1 px-2 bg-green-100 text-green-800 hover:bg-green-200 {{ $cleaningFilter === 'clean' ? 'active' : '' }}">
                        Clean
                    </button>
                    <button wire:click="$set('cleaningFilter', 'not_cleaned')" 
                            class="filter-button text-xs py-1 px-2 bg-red-100 text-red-800 hover:bg-red-200 {{ $cleaningFilter === 'not_cleaned' ? 'active' : '' }}">
                        Not Cleaned
                    </button>
                    <button wire:click="$set('cleaningFilter', 'in_progress')" 
                            class="filter-button text-xs py-1 px-2 bg-yellow-100 text-yellow-800 hover:bg-yellow-200 {{ $cleaningFilter === 'in_progress' ? 'active' : '' }}">
                        In Progress
                    </button>
                </div>
            </div>
            
            <!-- Search Bar -->
            <div class="flex-1">
                <h3 class="text-sm font-medium text-gray-700 mb-2">Search:</h3>
                <div class="relative">
                    <input type="text" wire:model.debounce.300ms="searchQuery" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#F8B803] focus:border-transparent"
                           placeholder="Search by room number, floor or category...">
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Results count -->
        <div class="mt-4 text-sm text-gray-600">
            <p>Showing {{ count($rooms) }} room{{ count($rooms) != 1 ? 's' : '' }}</p>
        </div>
    </div>
    
    <!-- Room grid display -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($rooms as $room)
            <div class="room-box {{ $room->room_status }} border-t-4 rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-all-smooth relative" 
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
                
                <div class="p-4">
                    <div class="flex justify-between items-center">
                        <h3 class="room-number text-xl font-bold">{{ $room->room_number }}</h3>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            {{ $room->room_type == 'single' ? 'bg-blue-100 text-blue-800' : 
                               ($room->room_type == 'double' ? 'bg-indigo-100 text-indigo-800' : 
                               'bg-purple-100 text-purple-800') }}">
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
                            <span class="font-medium">{{ ucfirst($room->room_status) }}</span>
                        </div>
                        
                        <div class="room-info-item">
                            <span>Cleaning</span>
                            <span class="font-medium room-status-badge {{ $room->cleaning_status }}"
                                  data-cleaning-status="{{ $room->cleaning_status }}">
                                {{ ucfirst(str_replace('_', ' ', $room->cleaning_status)) }}
                            </span>
                        </div>
                        
                        <!-- Check-in/Check-out status -->
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
                            <a href="{{ route('rooms.show', $room->id) }}" class="text-indigo-600 hover:text-indigo-900" title="View">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                </svg>
                            </a>
                            <a href="{{ route('rooms.edit', $room->id) }}" class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                </svg>
                            </a>
                            <form action="{{ route('rooms.destroy', $room->id) }}" method="POST" class="inline-block">
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
                        
                        <div class="flex items-center space-x-2">
                            <!-- Check in/out buttons -->
                            <div class="flex space-x-2">
                                @if($room->checkin_status != 'checked_in')
                                    <button wire:click="checkIn({{ $room->id }})" 
                                            class="text-green-600 hover:text-green-900" 
                                            title="Check In">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                @endif
                                
                                @if($room->checkout_status != 'checked_out')
                                    <button wire:click="checkOut({{ $room->id }})" 
                                            class="text-blue-600 hover:text-blue-900" 
                                            title="Check Out">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                @endif
                            </div>

                            <!-- Room badge icon -->
                            <div class="ml-2">
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

    <!-- Loading indicator removed for seamless real-time updates -->
</div>
