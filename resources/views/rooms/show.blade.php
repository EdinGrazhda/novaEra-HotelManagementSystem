<x-layouts.app :title="__('Show Room Details')">
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="bg-[#F8B803] p-6 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-semibold text-[#1B1B18]">Room {{ $room->room_number }} Details</h1>
                <p class="text-[#1B1B18] opacity-80">
                    {{ ucfirst($room->room_type) }} Room | Floor {{ $room->room_floor }} | 
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                        {{ $room->room_status == 'available' ? 'bg-green-100 text-green-800' : 
                        ($room->room_status == 'occupied' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800') }}">
                        {{ ucfirst($room->room_status) }}
                    </span>
                </p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('rooms.edit', $room) }}" class="px-4 py-2 bg-white text-[#1B1B18] font-medium rounded-md hover:bg-gray-100 transition duration-200 flex items-center">
                    <i class="fas fa-edit mr-2"></i> Edit
                </a>
                <a href="{{ route('rooms.index') }}" class="px-4 py-2 bg-white text-[#1B1B18] font-medium rounded-md hover:bg-gray-100 transition duration-200 flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Rooms
                </a>
            </div>
        </div>
        
        <div class="p-6">
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
                            alert.remove();
                        }
                    }, 5000);
                </script>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Basic Info Card -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h2 class="text-lg font-semibold border-b pb-2 mb-3">Basic Information</h2>
                    <div class="space-y-3">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Room Number</h3>
                            <p class="text-lg font-semibold text-[#1B1B18]">{{ $room->room_number }}</p>
                        </div>
                        
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Floor</h3>
                            <p class="text-lg font-semibold text-[#1B1B18]">{{ $room->room_floor }}</p>
                        </div>
                        
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Room Type</h3>
                            <p class="text-lg font-semibold text-[#1B1B18]">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $room->room_type == 'single' ? 'bg-blue-100 text-blue-800' : 
                                    ($room->room_type == 'double' ? 'bg-purple-100 text-purple-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($room->room_type) }}
                                </span>
                            </p>
                        </div>
                        
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Category</h3>
                            <p class="text-lg font-semibold text-[#1B1B18]">{{ $room->roomCategory->category_name ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Description</h3>
                            <p class="text-gray-700">{{ $room->room_description ?: 'No description available.' }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Status Card -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h2 class="text-lg font-semibold border-b pb-2 mb-3">Room Status</h2>
                    <div class="space-y-3">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Current Status</h3>
                            <p class="text-lg font-semibold text-[#1B1B18]">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $room->room_status == 'available' ? 'bg-green-100 text-green-800' : 
                                    ($room->room_status == 'occupied' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ ucfirst($room->room_status) }}
                                </span>
                            </p>
                        </div>
                        
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Cleaning Status</h3>
                            <p class="text-lg font-semibold text-[#1B1B18]">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ isset($room->cleaning_status) ? 
                                       ($room->cleaning_status == 'clean' ? 'bg-green-100 text-green-800' : 
                                       ($room->cleaning_status == 'not_cleaned' ? 'bg-red-100 text-red-800' : 
                                       'bg-yellow-100 text-yellow-800')) : 'bg-green-100 text-green-800' }}">
                                    {{ ucfirst(str_replace('_', ' ', $room->cleaning_status ?? 'clean')) }}
                                </span>
                            </p>
                            
                            @if($room->room_status == 'available' && isset($room->cleaning_status) && $room->cleaning_status == 'not_cleaned')
                                <div class="mt-1 text-xs text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    <span>This room needs cleaning</span>
                                </div>
                            @endif
                            
                            @if(isset($room->cleaning_notes) && !empty($room->cleaning_notes))
                                <div class="mt-2 bg-white p-2 rounded border border-gray-200">
                                    <h4 class="text-xs font-medium text-gray-500">Cleaning Notes:</h4>
                                    <p class="text-sm text-gray-700">{{ $room->cleaning_notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Check-in/out Card -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h2 class="text-lg font-semibold border-b pb-2 mb-3">Check-in & Check-out</h2>
                    <div class="space-y-3">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Check-in Status</h3>
                            <p class="flex items-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $room->checkin_status == 'checked_in' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }} mr-2">
                                    {{ $room->checkin_status == 'checked_in' ? 'Checked In' : 'Not Checked In' }}
                                </span>
                                @if($room->checkin_status == 'checked_in')
                                    <i class="fas fa-check-circle text-green-500"></i>
                                @endif
                            </p>
                            @if($room->checkin_time)
                                <p class="text-sm text-gray-500 mt-1">
                                    <i class="far fa-calendar-alt mr-1"></i>
                                    {{ \Carbon\Carbon::parse($room->checkin_time)->format('M d, Y g:i A') }}
                                </p>
                            @endif
                        </div>
                        
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Check-out Status</h3>
                            <p class="flex items-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $room->checkout_status == 'checked_out' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }} mr-2">
                                    {{ $room->checkout_status == 'checked_out' ? 'Checked Out' : 'Not Checked Out' }}
                                </span>
                                @if($room->checkout_status == 'checked_out')
                                    <i class="fas fa-check-circle text-blue-500"></i>
                                @endif
                            </p>
                            @if($room->checkout_time)
                                <p class="text-sm text-gray-500 mt-1">
                                    <i class="far fa-calendar-alt mr-1"></i>
                                    {{ \Carbon\Carbon::parse($room->checkout_time)->format('M d, Y g:i A') }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Actions Section -->
            <div class="mt-6">
                <h2 class="text-xl font-semibold mb-4">Room Actions</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Left column: Check-in/out and Room Status actions -->
                    <div>
                        <!-- Check-in/Check-out Actions Card -->
                        <div class="bg-white border border-gray-200 rounded-lg p-4 mb-4 shadow-sm">
                            <h3 class="font-medium text-gray-700 mb-3 flex items-center">
                                <i class="fas fa-key text-[#F8B803] mr-2"></i>
                                Check-in / Check-out Actions
                            </h3>
                            
                            <div class="flex flex-wrap gap-2">
                                @if($room->checkin_status != 'checked_in')
                                    <form action="{{ route('rooms.checkIn', $room) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 transition duration-200 flex items-center">
                                            <i class="fas fa-sign-in-alt mr-2"></i> Check-in
                                        </button>
                                    </form>
                                @else
                                    <button disabled class="px-4 py-2 bg-gray-400 text-white font-medium rounded-md cursor-not-allowed flex items-center">
                                        <i class="fas fa-sign-in-alt mr-2"></i> Already Checked-in
                                    </button>
                                @endif
                                
                                @if($room->checkin_status == 'checked_in' && $room->checkout_status != 'checked_out')
                                    <form action="{{ route('rooms.checkOut', $room) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="px-4 py-2 bg-green-600 text-white font-medium rounded-md hover:bg-green-700 transition duration-200 flex items-center">
                                            <i class="fas fa-sign-out-alt mr-2"></i> Check-out
                                        </button>
                                    </form>
                                @elseif($room->checkout_status == 'checked_out')
                                    <button disabled class="px-4 py-2 bg-gray-400 text-white font-medium rounded-md cursor-not-allowed flex items-center">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Already Checked-out
                                    </button>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Room Status Actions Card -->
                        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                            <h3 class="font-medium text-gray-700 mb-3 flex items-center">
                                <i class="fas fa-door-open text-[#F8B803] mr-2"></i>
                                Update Room Status
                            </h3>
                            
                            <div class="space-y-2">
                                @if($room->room_status == 'available')
                                    <div class="flex gap-2">
                                        <form action="{{ route('rooms.updateStatus', $room) }}" method="POST" class="flex-1">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="room_status" value="occupied">
                                            <button type="submit" class="w-full px-3 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 transition duration-200 flex items-center justify-center">
                                                <i class="fas fa-user-check mr-2"></i> Mark as Occupied
                                            </button>
                                        </form>
                                        
                                        <form action="{{ route('rooms.updateStatus', $room) }}" method="POST" class="flex-1">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="room_status" value="maintenance">
                                            <button type="submit" class="w-full px-3 py-2 bg-gray-600 text-white font-medium rounded-md hover:bg-gray-700 transition duration-200 flex items-center justify-center">
                                                <i class="fas fa-tools mr-2"></i> Mark for Maintenance
                                            </button>
                                        </form>
                                    </div>
                                @elseif($room->room_status == 'occupied')
                                    <div class="flex gap-2">
                                        <form action="{{ route('rooms.updateStatus', $room) }}" method="POST" class="flex-1">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="room_status" value="available">
                                            <button type="submit" class="w-full px-3 py-2 bg-green-600 text-white font-medium rounded-md hover:bg-green-700 transition duration-200 flex items-center justify-center">
                                                <i class="fas fa-check-circle mr-2"></i> Mark as Available
                                            </button>
                                        </form>
                                        
                                        <form action="{{ route('rooms.updateStatus', $room) }}" method="POST" class="flex-1">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="room_status" value="maintenance">
                                            <button type="submit" class="w-full px-3 py-2 bg-gray-600 text-white font-medium rounded-md hover:bg-gray-700 transition duration-200 flex items-center justify-center">
                                                <i class="fas fa-tools mr-2"></i> Mark for Maintenance
                                            </button>
                                        </form>
                                    </div>
                                @elseif($room->room_status == 'maintenance')
                                    <div class="flex gap-2">
                                        <form action="{{ route('rooms.updateStatus', $room) }}" method="POST" class="flex-1">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="room_status" value="available">
                                            <button type="submit" class="w-full px-3 py-2 bg-green-600 text-white font-medium rounded-md hover:bg-green-700 transition duration-200 flex items-center justify-center">
                                                <i class="fas fa-check-circle mr-2"></i> Mark as Available
                                            </button>
                                        </form>
                                        
                                        <form action="{{ route('rooms.updateStatus', $room) }}" method="POST" class="flex-1">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="room_status" value="occupied">
                                            <button type="submit" class="w-full px-3 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 transition duration-200 flex items-center justify-center">
                                                <i class="fas fa-user-check mr-2"></i> Mark as Occupied
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right column: Cleaning Status actions -->
                    <div>
                        <!-- Cleaning Status Actions Card -->
                        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                            <h3 class="font-medium text-gray-700 mb-3 flex items-center">
                                <i class="fas fa-broom text-[#F8B803] mr-2"></i>
                                Cleaning Status
                            </h3>
                            
                            <div class="grid grid-cols-3 gap-2 mb-4">
                                <form action="{{ route('rooms.updateCleaningStatus', $room) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="cleaning_status" value="clean">
                                    <button type="submit" class="w-full px-2 py-2 {{ $room->cleaning_status == 'clean' ? 'bg-green-700' : 'bg-green-600' }} text-white font-medium rounded-md hover:bg-green-700 transition duration-200 flex flex-col items-center justify-center">
                                        <i class="fas fa-check-circle text-lg mb-1"></i> 
                                        <span class="text-xs">Clean</span>
                                    </button>
                                </form>
                                
                                <form action="{{ route('rooms.updateCleaningStatus', $room) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="cleaning_status" value="in_progress">
                                    <button type="submit" class="w-full px-2 py-2 {{ $room->cleaning_status == 'in_progress' ? 'bg-yellow-700' : 'bg-yellow-600' }} text-white font-medium rounded-md hover:bg-yellow-700 transition duration-200 flex flex-col items-center justify-center">
                                        <i class="fas fa-clock text-lg mb-1"></i>
                                        <span class="text-xs">In Progress</span>
                                    </button>
                                </form>
                                
                                <form action="{{ route('rooms.updateCleaningStatus', $room) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="cleaning_status" value="not_cleaned">
                                    <button type="submit" class="w-full px-2 py-2 {{ $room->cleaning_status == 'not_cleaned' ? 'bg-red-700' : 'bg-red-600' }} text-white font-medium rounded-md hover:bg-red-700 transition duration-200 flex flex-col items-center justify-center">
                                        <i class="fas fa-times-circle text-lg mb-1"></i>
                                        <span class="text-xs">Not Cleaned</span>
                                    </button>
                                </form>
                            </div>
                            
                            <form action="{{ route('rooms.updateCleaningStatus', $room) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="cleaning_status" value="{{ $room->cleaning_status ?? 'clean' }}">
                                <div class="flex flex-col">
                                    <label for="cleaning_notes" class="mb-1 text-sm font-medium text-gray-700">Cleaning Notes:</label>
                                    <div class="flex">
                                        <input type="text" id="cleaning_notes" name="cleaning_notes" placeholder="Add cleaning notes..." 
                                               class="flex-grow px-3 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-[#F8B803]"
                                               value="{{ $room->cleaning_notes }}">
                                        <button type="submit" class="px-4 py-2 bg-[#F8B803] text-[#1B1B18] rounded-r-md hover:bg-yellow-500">
                                            <i class="fas fa-save"></i>
                                        </button>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Add any special cleaning instructions or notes here.</p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include script to maintain room colors when changing cleaning status -->
<script src="{{ asset('js/room-cleaning-updates.js') }}"></script>

@if(session('triggerDashboardRefresh'))
<script>
    // This script will send an event to refresh the dashboard when triggered from a controller
    document.addEventListener('DOMContentLoaded', function() {
        console.log("Dispatching dashboard refresh event from room show page");
        // Create and dispatch a custom event for the dashboard to listen to
        const event = new CustomEvent('refresh-dashboard', { 
            bubbles: true,
            detail: { source: 'room-show', timestamp: new Date().getTime() }
        });
        document.dispatchEvent(event);
        
        // If there's another dashboard window open, try to refresh that too
        try {
            if (window.opener && !window.opener.closed) {
                window.opener.dispatchEvent(event);
            }
        } catch (e) {
            console.log("Could not refresh parent window", e);
        }
    });
</script>
@endif
</x-layouts.app>
