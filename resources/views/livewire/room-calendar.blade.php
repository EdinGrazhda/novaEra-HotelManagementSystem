<div wire:poll.5s class="bg-white rounded-lg shadow-lg p-2 lg:p-4">
    <style>        /* Calendar-specific styles */
        .calendar-cell-available {
            @apply bg-green-400 hover:bg-green-500;
            transition: all 0.2s ease-in-out;
        }
        
        .calendar-cell-occupied {
            @apply bg-red-400 hover:bg-red-500;
            transition: all 0.2s ease-in-out;
        }
        
        .calendar-cell-maintenance {
            @apply bg-yellow-400 hover:bg-yellow-500;
            transition: all 0.2s ease-in-out;
        }
        
        /* New class for booking period (check-in to check-out) */
        .calendar-cell-booking {
            @apply bg-red-600 hover:bg-red-700;
            transition: all 0.2s ease-in-out;
        }
        
        .calendar-grid {
            @apply shadow-sm rounded-sm overflow-hidden;
            max-width: 100%;
        }
        
        .room-hover-effect:hover {
            @apply bg-gray-50;
            transform: translateZ(0);
        }
        
        /* Room status badges */
        .status-badge {
            @apply text-xs px-2 py-0.5 rounded-full font-medium;
        }
        
        .status-badge-available {
            @apply bg-green-100 text-green-800;
        }
        
        .status-badge-occupied {
            @apply bg-red-100 text-red-800;
        }
        
        .status-badge-maintenance {
            @apply bg-yellow-100 text-yellow-800;
        }
        
        /* Animation for status changes */
        @keyframes pulse-highlight {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.6; }
        }
        
        .pulse-animation {
            animation: pulse-highlight 2s ease-in-out;
        }
    </style>
    
    <!-- Calendar Header -->
    <div class="mb-6">
        <div class="flex flex-col md:flex-row justify-between items-center mb-4">
            <!-- Month Navigation -->
            <div class="flex items-center space-x-2 mb-3 md:mb-0">
                <button wire:click="navigatePrevMonth" class="p-2 rounded-full hover:bg-gray-100 transition-all-smooth">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                
                <h2 class="text-xl font-semibold text-gray-800">{{ $currentMonth }}</h2>
                
                <button wire:click="navigateNextMonth" class="p-2 rounded-full hover:bg-gray-100 transition-all-smooth">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
                
                <button wire:click="navigateToday" class="ml-2 px-3 py-1 border border-blue-500 text-blue-500 rounded-md hover:bg-blue-50 transition-all-smooth">
                    Today
                </button>
            </div>
            
            <!-- Filters -->
            <div class="flex flex-wrap gap-2 justify-center">
                <!-- Status Filter -->
                <select wire:model.live="statusFilter" class="border border-gray-300 rounded-md px-3 py-1 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="all">All Status</option>
                    <option value="available">Available</option>
                    <option value="occupied">Occupied</option>
                    <option value="maintenance">Maintenance</option>
                </select>
                
                <!-- Floor Filter -->
                <select wire:model.live="floorFilter" class="border border-gray-300 rounded-md px-3 py-1 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="all">All Floors</option>
                    @foreach($floors as $floor)
                        <option value="{{ $floor }}">Floor {{ $floor }}</option>
                    @endforeach
                </select>
                
                <!-- Category Filter -->
                <select wire:model.live="categoryFilter" class="border border-gray-300 rounded-md px-3 py-1 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="all">All Categories</option>
                    @foreach($categories as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
          <!-- Legend -->        <div class="flex flex-wrap gap-4 justify-center mt-4 px-2">
            <div class="flex items-center bg-white px-3 py-1 rounded-full shadow-sm">
                <span class="w-6 h-6 bg-green-400 rounded-sm inline-block mr-1"></span>
                <span class="text-sm">Available</span>
            </div>
            <div class="flex items-center bg-white px-3 py-1 rounded-full shadow-sm">
                <span class="w-6 h-6 bg-red-400 rounded-sm inline-block mr-1"></span>
                <span class="text-sm">Occupied</span>
            </div>
            <div class="flex items-center bg-white px-3 py-1 rounded-full shadow-sm">
                <span class="w-6 h-6 bg-yellow-400 rounded-sm inline-block mr-1"></span>
                <span class="text-sm">Maintenance</span>
            </div>
            <div class="flex items-center bg-white px-3 py-1 rounded-full shadow-sm">
                <span class="w-6 h-6 bg-red-600 rounded-sm inline-block mr-1"></span>
                <span class="text-sm">Booked Period</span>
            </div>
        </div>
        
        <!-- Mobile helper message -->
        <div class="md:hidden text-center mt-4 text-sm text-gray-500 bg-gray-50 p-2 rounded">
            <p>Scroll horizontally to view more dates</p>
        </div>
    </div>
    
    @if(session('success'))
        <div id="success-alert" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <div class="flex justify-between items-center">
                <p>{{ session('success') }}</p>
                <span class="text-green-700 hover:text-green-800 cursor-pointer" onclick="document.getElementById('success-alert').remove()">×</span>
            </div>
        </div>
    @endif        <!-- Calendar Grid -->
    <div class="overflow-x-auto">
        <table class="w-full border-collapse min-w-max">
            <thead>
                <tr class="text-sm font-medium text-gray-700 bg-gray-100">
                    <th class="border border-gray-200 p-2 sticky left-0 bg-gray-100 z-10 min-w-[120px]">Room #</th>
                    @foreach($calendarData['days'] as $day)
                        <th class="border border-gray-200 p-2 min-w-10 text-center {{ $day['isToday'] ? 'bg-blue-100' : ($day['isWeekend'] ? 'bg-gray-50' : '') }}">
                            <div class="flex flex-col">
                                <span class="text-xs text-gray-500">
                                    {{ \Carbon\Carbon::createFromFormat('Y-m-d', $day['date'])->format('D') }}
                                </span>
                                <span class="font-medium">{{ $day['day'] }}</span>
                            </div>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @forelse($calendarData['rooms'] as $room)
                    <tr class="hover:bg-gray-50 transition-colors">                        <td class="border border-gray-200 p-2 sticky left-0 bg-white z-10 room-hover-effect" data-room-id="{{ $room['id'] }}">
                            <div class="flex flex-col">
                                <div class="flex justify-between items-center">
                                    <span class="font-semibold text-gray-800">{{ $room['room_number'] }}</span>
                                    @php
                                        $statusClass = 'status-badge-available';
                                        if ($room['room_status'] === 'occupied') $statusClass = 'status-badge-occupied';
                                        if ($room['room_status'] === 'maintenance') $statusClass = 'status-badge-maintenance';
                                    @endphp
                                    <span class="status-badge {{ $statusClass }}">{{ ucfirst($room['room_status']) }}</span>
                                </div>
                                
                                <div class="flex items-center text-xs text-gray-500 mt-0.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    Floor {{ $room['room_floor'] }}
                                </div>
                                
                                <div class="flex items-center text-xs text-gray-500 mt-0.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                    </svg>
                                    {{ $room['category_name'] }}
                                </div>
                                
                                <!-- Action Buttons -->
                                <div class="mt-2 flex gap-1">                                    @if($room['room_status'] != 'occupied')
                                        <button wire:click="checkIn({{ $room['id'] }})" class="px-2 py-1 text-xs bg-red-500 hover:bg-red-600 text-white rounded flex items-center transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14" />
                                            </svg>
                                            Check In
                                        </button>
                                    @endif
                                    
                                    @if($room['room_status'] == 'occupied')
                                        <button wire:click="checkOut({{ $room['id'] }})" class="px-2 py-1 text-xs bg-red-500 hover:bg-red-600 text-white rounded flex items-center transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7" />
                                            </svg>
                                            Check Out
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </td>
                        
                        @foreach($calendarData['days'] as $day)                                @php 
                                $dateData = $room['dates'][$day['date']] ?? [
                                    'status' => 'unknown', 
                                    'is_checked_in' => false, 
                                    'is_checked_out' => false, 
                                    'is_booking_period' => false
                                ];
                                $status = $dateData['status'];
                                $isCheckinDate = $dateData['is_checked_in'];
                                $isCheckoutDate = $dateData['is_checked_out'];
                                $isBookingPeriod = $dateData['is_booking_period'];
                                
                                // Determine cell color class
                                if ($isCheckinDate || $isCheckoutDate || $isBookingPeriod) {
                                    $cellClass = 'calendar-cell-booking'; // New class for any booked day
                                } elseif ($status === 'occupied') {
                                    $cellClass = 'calendar-cell-occupied';
                                } elseif ($status === 'maintenance') {
                                    $cellClass = 'calendar-cell-maintenance';
                                } else {
                                    $cellClass = 'calendar-cell-available';
                                }
                                
                                $isToday = $day['isToday'] ? 'ring-2 ring-blue-300' : '';
                                $isWeekend = $day['isWeekend'] ? 'opacity-95' : '';
                            @endphp
                            
                            <td class="border border-gray-200 p-0 text-center relative" 
                                data-date="{{ $day['date'] }}" 
                                data-room="{{ $room['room_number'] }}" 
                                data-status="{{ $status }}">
                                <div class="w-full h-full">                                    <div class="{{ $cellClass }} {{ $isToday }} {{ $isWeekend }} h-8 w-full flex items-center justify-center cursor-pointer"
                                        x-data="{showTooltip: false}"
                                        @mouseenter="showTooltip = true" 
                                        @mouseleave="showTooltip = false">
                                        <!-- Empty div for pure color display without text -->
                                        
                                        <!-- Tooltip -->
                                        <div x-show="showTooltip" 
                                            x-transition:enter="transition ease-out duration-200" 
                                            x-transition:enter-start="opacity-0 scale-95" 
                                            x-transition:enter-end="opacity-100 scale-100" 
                                            x-transition:leave="transition ease-in duration-100" 
                                            x-transition:leave-start="opacity-100 scale-100" 
                                            x-transition:leave-end="opacity-0 scale-95"
                                            class="absolute z-50 w-48 px-2 py-1 text-sm text-white bg-gray-700 rounded-lg shadow-lg"
                                            style="bottom: 100%; left: 50%; transform: translateX(-50%);">
                                            <div class="font-medium">Room {{ $room['room_number'] }}</div>
                                            <div>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $day['date'])->format('M j, Y') }}</div>                                            <div class="capitalize">
                                                Status: 
                                                <span class="{{ $status === 'available' ? 'text-green-300' : ($status === 'occupied' ? 'text-red-300' : 'text-yellow-300') }}">
                                                    {{ $status }}
                                                </span>
                                            </div>
                                            @if($isCheckinDate)
                                                <div class="text-red-300 font-semibold">Check-in date</div>
                                            @endif
                                            @if($isCheckoutDate)
                                                <div class="text-red-300 font-semibold">Check-out date</div>
                                            @endif
                                            @if($isBookingPeriod && !$isCheckinDate && !$isCheckoutDate)
                                                <div class="text-red-300">Booked period</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($calendarData['days']) + 1 }}" class="border border-gray-200 p-4 text-center text-gray-500">
                            No rooms match the selected filters
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
      <script>
        document.addEventListener('livewire:init', () => {
            // Configure Livewire for smooth recovery from errors
            Livewire.hook('request', ({ fail }) => {
                fail(({ status, content }) => {
                    console.log('Auto-recovering from error for seamless UX');
                    return false;
                });
            });
            
            // Add animation when a room status is updated
            Livewire.on('statusUpdated', ({ roomId, action }) => {
                const actionMap = {
                    'checkin': 'checked in',
                    'checkout': 'checked out'
                };
                
                // Add a pulse animation to the affected room row
                document.querySelectorAll(`td[data-room-id="${roomId}"]`).forEach(el => {
                    el.classList.add('bg-green-50');
                    
                    // Highlight cells for this room
                    const roomNumber = el.querySelector('.font-semibold').textContent.trim();
                    document.querySelectorAll(`td[data-room="${roomNumber}"]`).forEach(cell => {
                        cell.querySelector('div > div').classList.add('pulse-animation');
                    });
                    
                    // Remove highlight after animation completes
                    setTimeout(() => {
                        el.classList.remove('bg-green-50');
                    }, 2000);
                });
            });
            
            // Additional client-side enhancements
            window.addEventListener('load', () => {
                // Scroll to today if it exists in the calendar
                const today = document.querySelector('th.bg-blue-100');
                if (today) {
                    // Add a slight delay to ensure the calendar is fully rendered
                    setTimeout(() => {
                        today.scrollIntoView({ behavior: 'smooth', block: 'center', inline: 'center' });
                    }, 300);
                }
                
                // Hide success message after 3 seconds
                const successAlert = document.getElementById('success-alert');
                if (successAlert) {
                    setTimeout(() => {
                        successAlert.classList.add('opacity-0');
                        setTimeout(() => {
                            successAlert.remove();
                        }, 300);
                    }, 3000);
                }
            });
            
            // Add keyboard navigation
            document.addEventListener('keydown', (e) => {
                // Left arrow key = previous month
                if (e.key === 'ArrowLeft' && !e.ctrlKey && !e.altKey) {
                    const prevButton = document.querySelector('[wire\\:click="navigatePrevMonth"]');
                    if (prevButton) prevButton.click();
                }
                
                // Right arrow key = next month
                if (e.key === 'ArrowRight' && !e.ctrlKey && !e.altKey) {
                    const nextButton = document.querySelector('[wire\\:click="navigateNextMonth"]');
                    if (nextButton) nextButton.click();
                }
                
                // T key = today
                if (e.key === 't' && !e.ctrlKey && !e.altKey) {
                    const todayButton = document.querySelector('[wire\\:click="navigateToday"]');
                    if (todayButton) todayButton.click();
                }
            });
        });
    </script>
</div>
