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
    
   
    
    <!-- Filter section -->
    <div class="mb-6 bg-white rounded-lg shadow-md p-4 border border-gray-200">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
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
                    <input type="text" wire:model="searchQuery" 
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
            <p>Showing {{ count($cleaning) }} room{{ count($cleaning) != 1 ? 's' : '' }}</p>
        </div>
    </div>
    
    <!-- Room grid display for cleaning -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($cleaning as $room)
            <div class="room-box border-t-4 rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-all-smooth" 
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
                    <!-- Cleaning status action buttons using Livewire -->
                    <div class="flex flex-col gap-2">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-600">Change Cleaning Status:</span>
                        </div>
                        
                        <div class="grid grid-cols-3 gap-2">
                            <button wire:click="updateCleaningStatus({{ $room->id }}, 'clean', '{{ $room->cleaning_notes }}')" 
                                    class="w-full h-8 px-2 py-1 text-xs bg-green-100 text-green-800 rounded hover:bg-green-200 flex items-center justify-center {{ $room->cleaning_status == 'clean' ? 'border-2 border-green-500' : '' }}">
                                Clean
                            </button>
                            
                            <button wire:click="updateCleaningStatus({{ $room->id }}, 'not_cleaned', '{{ $room->cleaning_notes }}')" 
                                    class="w-full h-8 px-2 py-1 text-xs bg-red-100 text-red-800 rounded hover:bg-red-200 flex items-center justify-center {{ $room->cleaning_status == 'not_cleaned' ? 'border-2 border-red-500' : '' }}">
                                Not Cleaned
                            </button>
                            
                            <button wire:click="updateCleaningStatus({{ $room->id }}, 'in_progress', '{{ $room->cleaning_notes }}')" 
                                    class="w-full h-8 px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded hover:bg-yellow-200 flex items-center justify-center {{ $room->cleaning_status == 'in_progress' ? 'border-2 border-yellow-500' : '' }}">
                                In Progress
                            </button>
                        </div>
                        
                        <!-- Cleaning notes form -->
                        <div class="mt-2 flex">
                            <input type="text" wire:model.defer="cleaningNotes.{{ $room->id }}" placeholder="Add cleaning notes..." 
                                   class="w-full px-3 py-1 text-xs border border-gray-300 rounded-l-md focus:outline-none focus:ring-1 focus:ring-[#F8B803]"
                                   value="{{ $room->cleaning_notes }}">
                            <button wire:click="updateCleaningStatus({{ $room->id }}, '{{ $room->cleaning_status }}', $event.target.previousElementSibling.value)" 
                                    class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-r-md hover:bg-blue-200 border border-blue-300">
                                Save
                            </button>
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

    <!-- Loading indicator removed for seamless real-time updates -->
</div>
