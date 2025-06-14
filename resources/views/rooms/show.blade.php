<x-layouts.app :title="__('Show Room Details')">
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="bg-[#F8B803] p-6 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-semibold text-[#1B1B18]">Room Details</h1>
                <p class="text-[#1B1B18] opacity-80">View detailed information about this room</p>
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

            <div class="flex flex-col md:flex-row md:gap-8">
                <div class="md:w-1/2 mb-6 md:mb-0">
                    <div class="aspect-video bg-gray-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-bed text-6xl text-gray-300"></i>
                    </div>
                </div>
                
                <div class="md:w-1/2">
                    <div class="grid grid-cols-1 gap-4">
                        <div class="border-b pb-3">
                            <h3 class="text-sm font-medium text-gray-500">Room Number</h3>
                            <p class="text-lg font-semibold text-[#1B1B18]">{{ $room->room_number }}</p>
                        </div>
                        
                        <div class="border-b pb-3">
                            <h3 class="text-sm font-medium text-gray-500">Floor</h3>
                            <p class="text-lg font-semibold text-[#1B1B18]">{{ $room->room_floor }}</p>
                        </div>
                        
                        <div class="border-b pb-3">
                            <h3 class="text-sm font-medium text-gray-500">Room Type</h3>
                            <p class="text-lg font-semibold text-[#1B1B18]">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $room->room_type == 'single' ? 'bg-blue-100 text-blue-800' : 
                                    ($room->room_type == 'double' ? 'bg-purple-100 text-purple-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($room->room_type) }}
                                </span>
                            </p>
                        </div>
                        
                        <div class="border-b pb-3">
                            <h3 class="text-sm font-medium text-gray-500">Status</h3>
                            <p class="text-lg font-semibold text-[#1B1B18]">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $room->room_status == 'available' ? 'bg-green-100 text-green-800' : 
                                    ($room->room_status == 'occupied' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ ucfirst($room->room_status) }}
                                </span>
                            </p>
                        </div>
                        
                        <div class="border-b pb-3">
                            <h3 class="text-sm font-medium text-gray-500">Cleaning Status</h3>
                            <p class="text-lg font-semibold text-[#1B1B18]">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ isset($room->cleaning_status) ? 
                                       ($room->cleaning_status == 'clean' ? 'bg-green-100 text-green-800' : 
                                       ($room->cleaning_status == 'not_cleaned' ? 'bg-red-100 text-red-800' : 
                                       'bg-yellow-100 text-yellow-800')) : 'bg-green-100 text-green-800' }}">
                                    {{ ucfirst(str_replace('_', ' ', $room->cleaning_status ?? 'clean')) }}
                                </span>
                                
                                @if($room->room_status == 'available' && isset($room->cleaning_status) && $room->cleaning_status == 'not_cleaned')
                                    <span class="ml-2 text-xs text-red-600">
                                        <i class="fas fa-exclamation-circle"></i>
                                        This room needs cleaning
                                    </span>
                                @endif
                            </p>
                            
                            @if(isset($room->cleaning_notes) && !empty($room->cleaning_notes))
                                <div class="mt-2">
                                    <h4 class="text-xs font-medium text-gray-500">Cleaning Notes:</h4>
                                    <p class="text-sm text-gray-700">{{ $room->cleaning_notes }}</p>
                                </div>
                            @endif
                        </div>
                        
                        <div class="border-b pb-3">
                            <h3 class="text-sm font-medium text-gray-500">Category</h3>
                            <p class="text-lg font-semibold text-[#1B1B18]">{{ $room->roomCategory->category_name ?? 'N/A' }}</p>
                        </div>
                        
                        <div class="border-b pb-3">
                            <h3 class="text-sm font-medium text-gray-500">Check-in Status</h3>
                            <p class="text-lg font-semibold">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $room->checkin_status == 'checked_in' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $room->checkin_status == 'checked_in' ? 'Checked In' : 'Not Checked In' }}
                                </span>
                            </p>
                            @if($room->checkin_time)
                                <p class="text-sm text-gray-500 mt-1">
                                    Check-in Time: {{ \Carbon\Carbon::parse($room->checkin_time)->format('M d, Y g:i A') }}
                                </p>
                            @endif
                        </div>
                        
                        <div class="border-b pb-3">
                            <h3 class="text-sm font-medium text-gray-500">Check-out Status</h3>
                            <p class="text-lg font-semibold">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $room->checkout_status == 'checked_out' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $room->checkout_status == 'checked_out' ? 'Checked Out' : 'Not Checked Out' }}
                                </span>
                            </p>
                            @if($room->checkout_time)
                                <p class="text-sm text-gray-500 mt-1">
                                    Check-out Time: {{ \Carbon\Carbon::parse($room->checkout_time)->format('M d, Y g:i A') }}
                                </p>
                            @endif
                        </div>
                        
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Description</h3>
                            <p class="text-gray-700">{{ $room->room_description ?: 'No description available.' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-8 border-t pt-6">                <h3 class="text-lg font-semibold mb-4">Actions</h3>
                <div class="flex flex-wrap gap-3">
                    
                    <!-- Check-in/Check-out Actions -->
                    <div class="w-full p-4 mb-3 bg-gray-50 rounded-lg">
                        <h4 class="font-medium mb-3">Check-in / Check-out Actions</h4>
                        <div class="flex space-x-3">
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
                    
                    <!-- Room Status Actions -->
                    <div class="w-full md:w-1/2 p-4 bg-gray-50 rounded-lg">
                        <h4 class="font-medium mb-3">Room Status</h4>
                    
                        @if($room->room_status == 'available')
                            <form action="{{ route('rooms.updateStatus', $room) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="room_status" value="occupied">
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 transition duration-200 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    Mark as Occupied
                                </button>
                            </form>
                            
                            <form action="{{ route('rooms.updateStatus', $room) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="room_status" value="maintenance">
                                <button type="submit" class="px-4 py-2 bg-gray-600 text-white font-medium rounded-md hover:bg-gray-700 transition duration-200 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                                    </svg>
                                    Mark for Maintenance
                                </button>
                            </form>
                        @elseif($room->room_status == 'occupied')
                            <form action="{{ route('rooms.updateStatus', $room) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="room_status" value="available">
                                <button type="submit" class="px-4 py-2 bg-green-600 text-white font-medium rounded-md hover:bg-green-700 transition duration-200 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    Mark as Available
                                </button>
                            </form>
                            
                            <form action="{{ route('rooms.updateStatus', $room) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="room_status" value="maintenance">
                                <button type="submit" class="px-4 py-2 bg-gray-600 text-white font-medium rounded-md hover:bg-gray-700 transition duration-200 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                                    </svg>
                                    Mark for Maintenance
                                </button>
                            </form>
                        @elseif($room->room_status == 'maintenance')
                            <form action="{{ route('rooms.updateStatus', $room) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="room_status" value="available">
                                <button type="submit" class="px-4 py-2 bg-green-600 text-white font-medium rounded-md hover:bg-green-700 transition duration-200 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                    </svg>
                                    Mark as Available
                                </button>
                            </form>
                            
                            <form action="{{ route('rooms.updateStatus', $room) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="room_status" value="occupied">
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 transition duration-200 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    Mark as Occupied
                                </button>
                            </form>
                        @endif
                    </div>
                    
                    <!-- Cleaning Status Actions -->
                    <div class="w-full md:w-1/2 p-4 bg-gray-50 rounded-lg">
                        <h4 class="font-medium mb-3">Cleaning Status</h4>
                        
                        <div class="flex space-x-2 mb-3">
                            <form action="{{ route('rooms.updateCleaningStatus', $room) }}" method="POST" class="flex-1">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="cleaning_status" value="clean">
                                <button type="submit" class="w-full px-2 py-2 {{ $room->cleaning_status == 'clean' ? 'bg-green-700' : 'bg-green-600' }} text-white font-medium rounded-md hover:bg-green-700 transition duration-200 flex items-center justify-center">
                                    <i class="fas fa-check-circle mr-2"></i> Clean
                                </button>
                            </form>
                            
                            <form action="{{ route('rooms.updateCleaningStatus', $room) }}" method="POST" class="flex-1">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="cleaning_status" value="not_cleaned">
                                <button type="submit" class="w-full px-2 py-2 {{ $room->cleaning_status == 'not_cleaned' ? 'bg-red-700' : 'bg-red-600' }} text-white font-medium rounded-md hover:bg-red-700 transition duration-200 flex items-center justify-center">
                                    <i class="fas fa-times-circle mr-2"></i> Not Cleaned
                                </button>
                            </form>
                            
                            <form action="{{ route('rooms.updateCleaningStatus', $room) }}" method="POST" class="flex-1">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="cleaning_status" value="in_progress">
                                <button type="submit" class="w-full px-2 py-2 {{ $room->cleaning_status == 'in_progress' ? 'bg-yellow-700' : 'bg-yellow-600' }} text-white font-medium rounded-md hover:bg-yellow-700 transition duration-200 flex items-center justify-center">
                                    <i class="fas fa-clock mr-2"></i> In Progress
                                </button>
                            </form>
                        </div>
                        
                        <form action="{{ route('rooms.updateCleaningStatus', $room) }}" method="POST" class="mt-4">
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
                                        Save Notes
                                    </button>
                                </div>
                            </div>
                        </form>
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
