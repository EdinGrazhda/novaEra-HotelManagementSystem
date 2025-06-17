<div>
    <div wire:poll.15s.keep-alive="poll" class="bg-white rounded-lg shadow-lg overflow-hidden mb-8">
        <div class="p-4 bg-[#f9b903] text-[#1B1B18] flex justify-between items-center">
            <h2 class="text-xl font-semibold">Room Status Overview</h2>            <div class="flex items-center">
                <span class="text-xs text-[#1B1B18] opacity-80 mr-2">Auto-updating</span>
                <button wire:click="togglePolling" class="focus:outline-none" title="Toggle auto-updating">
                    <span class="relative flex h-3 w-3">
                        @if($pollingActive)
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                        @else
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-gray-300"></span>
                        @endif
                    </span>
                </button>
            </div>
        </div>
        <div class="p-6">
            <!-- Total Rooms Summary -->
            <div class="text-center mb-6">
                <p class="text-gray-500 mb-1">Total Rooms</p>
                <div class="flex items-center justify-center">
                    <div class="rounded-full bg-[#f9b903] p-3 mr-3">
                        <i class="fas fa-bed text-white text-xl"></i>
                    </div>
                    <span class="text-4xl font-bold text-gray-800">{{ $totalRooms ?? 0 }}</span>
                </div>
            </div>

            <!-- Room Status Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mt-4">
                <!-- Available Rooms -->
                <div class="bg-white border border-gray-100 rounded-lg shadow-sm p-4 fade-in-up">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="font-semibold text-gray-700">Available</h3>
                        <span class="rounded-full bg-green-100 text-green-700 px-2 py-0.5 text-xs">Ready</span>
                    </div>
                    <div class="flex items-center mb-2">
                        <div class="rounded-full bg-green-500 p-2 mr-2">
                            <i class="fas fa-check-circle text-white text-sm"></i>
                        </div>
                        <p class="text-2xl font-bold text-gray-800 room-status-value" data-track-id="available-rooms">{{ $availableRooms ?? 0 }}</p>
                    </div>
                    <div class="relative pt-1">
                        <div class="flex items-center justify-between mb-1">
                            <div>                                <span class="text-xs font-semibold inline-block text-green-600 percent-value" data-track-id="available-percent">
                                    {{ $totalRooms > 0 ? round(($availableRooms / $totalRooms) * 100) : 0 }}% of rooms
                                </span>
                            </div>
                        </div>                        <div class="overflow-hidden h-2 mb-1 text-xs flex rounded bg-green-100">
                            <div wire:key="available-bar-{{ $availableRooms }}" style="width: {{ $totalRooms > 0 ? ($availableRooms / $totalRooms) * 100 : 0 }}%" class="progress-bar shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-green-500"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Occupied Rooms -->
                <div class="bg-white border border-gray-100 rounded-lg shadow-sm p-4 fade-in-up">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="font-semibold text-gray-700">Occupied</h3>
                        <span class="rounded-full bg-blue-100 text-blue-700 px-2 py-0.5 text-xs">In Use</span>
                    </div>
                    <div class="flex items-center mb-2">
                        <div class="rounded-full bg-blue-500 p-2 mr-2">
                            <i class="fas fa-user text-white text-sm"></i>
                        </div>
                        <p class="text-2xl font-bold text-gray-800 room-status-value" data-track-id="occupied-rooms">{{ $occupiedRooms ?? 0 }}</p>
                    </div>
                    <div class="relative pt-1">
                        <div class="flex items-center justify-between mb-1">
                            <div>                                <span class="text-xs font-semibold inline-block text-blue-600 percent-value" data-track-id="occupied-percent">
                                    {{ $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100) : 0 }}% of rooms
                                </span>
                            </div>
                        </div>                        <div class="overflow-hidden h-2 mb-1 text-xs flex rounded bg-blue-100">
                            <div wire:key="occupied-bar-{{ $occupiedRooms }}" style="width: {{ $totalRooms > 0 ? ($occupiedRooms / $totalRooms) * 100 : 0 }}%" class="progress-bar shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-blue-500"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Maintenance Rooms -->
                <div class="bg-white border border-gray-100 rounded-lg shadow-sm p-4 fade-in-up">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="font-semibold text-gray-700">Maintenance</h3>
                        <span class="rounded-full bg-yellow-100 text-yellow-700 px-2 py-0.5 text-xs">Unavailable</span>
                    </div>
                    <div class="flex items-center mb-2">
                        <div class="rounded-full bg-yellow-500 p-2 mr-2">
                            <i class="fas fa-tools text-white text-sm"></i>
                        </div>
                        <p class="text-2xl font-bold text-gray-800 room-status-value" data-track-id="maintenance-rooms">{{ $maintenanceRooms ?? 0 }}</p>
                    </div>
                    <div class="relative pt-1">
                        <div class="flex items-center justify-between mb-1">
                            <div>                                <span class="text-xs font-semibold inline-block text-yellow-600 percent-value" data-track-id="maintenance-percent">
                                    {{ $totalRooms > 0 ? round(($maintenanceRooms / $totalRooms) * 100) : 0 }}% of rooms
                                </span>
                            </div>
                        </div>                        <div class="overflow-hidden h-2 mb-1 text-xs flex rounded bg-yellow-100">
                            <div wire:key="maintenance-bar-{{ $maintenanceRooms }}" style="width: {{ $totalRooms > 0 ? ($maintenanceRooms / $totalRooms) * 100 : 0 }}%" class="progress-bar shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-yellow-500"></div>
                        </div>
                    </div>
                </div>
            </div>            <!-- Last updated timestamp -->
            <div class="text-xs text-gray-400 mt-4 flex justify-between items-center">
                <button wire:click="loadRoomStatusData" class="text-xs text-blue-500 hover:text-blue-700 flex items-center">
                    <i class="fas fa-sync mr-1"></i> Refresh Now
                </button>
                
                <div>
                    <i class="fas fa-clock mr-1"></i> Last updated: <span id="room-status-last-updated" class="highlight-on-change">{{ $lastUpdated ?? now()->format('H:i:s') }}</span>
                </div>
            </div></div>
    </div>      <script>
        function highlightElement(element) {
            if (!element) return;
            element.classList.add('highlight-change');
            setTimeout(() => {
                element.classList.remove('highlight-change');
            }, 2000);
        }
        
        // When Livewire updates, highlight all changed values
        document.addEventListener('livewire:update', function(event) {
            console.log('Livewire update detected', new Date().toLocaleTimeString());
            
            // Highlight changed room status values
            document.querySelectorAll('.room-status-value').forEach(element => {
                highlightElement(element);
            });
            
            // Highlight percentage values
            document.querySelectorAll('.percent-value').forEach(element => {
                highlightElement(element);
            });
            
            // Highlight the timestamp
            const timestampElement = document.getElementById('room-status-last-updated');
            if(timestampElement) {
                timestampElement.classList.add('highlight-timestamp');
                setTimeout(() => {
                    timestampElement.classList.remove('highlight-timestamp');
                }, 2000);
            }
            
            // Animate progress bars
            document.querySelectorAll('.progress-bar').forEach(element => {
                element.classList.add('animate-width-change');
                setTimeout(() => {
                    element.classList.remove('animate-width-change');
                }, 700);
            });
        });
        
        // Listen for Livewire events
        document.addEventListener('livewire:init', () => {
            // Listen for status update events from any component
            Livewire.on('statusUpdated', (data) => {
                console.log('Room status updated event received:', data);
            });
            
            // Listen for specific room status updates from our component
            Livewire.on('roomStatusUpdated', (data) => {
                console.log('Real-time room status updated at:', data.timestamp);
                
                // Update the last updated timestamp
                const timestampElement = document.getElementById('room-status-last-updated');
                if(timestampElement) {
                    timestampElement.textContent = data.timestamp;
                    highlightElement(timestampElement);
                }
            });
        });
    </script>
      <style>
        .highlight-change {
            animation: pulse-highlight 2s ease-in-out;
        }
        
        .highlight-timestamp {
            font-weight: bold;
            animation: pulse-highlight-text 2s ease-in-out;
        }
        
        @keyframes pulse-highlight {
            0% { background-color: transparent; }
            25% { background-color: rgba(249, 185, 3, 0.3); }
            75% { background-color: rgba(249, 185, 3, 0.3); }
            100% { background-color: transparent; }
        }
        
        @keyframes pulse-highlight-text {
            0% { color: #9ca3af; }
            25% { color: var(--brand-gold); }
            75% { color: var(--brand-gold); }
            100% { color: #9ca3af; }
        }
        
        .progress-bar {
            transition: width 0.7s ease-in-out;
        }
        
        .animate-width-change {
            animation: width-pulse 0.7s ease-in-out;
        }
        
        @keyframes width-pulse {
            0% { opacity: 0.7; }
            50% { opacity: 0.9; }
            100% { opacity: 1; }
        }
        
        .fade-in-up {
            animation: fadeInUp 0.5s ease-out;
        }
        
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-ping {
            animation: ping 1.5s cubic-bezier(0, 0, 0.2, 1) infinite;
        }
        
        @keyframes ping {
            75%, 100% {
                transform: scale(1.5);
                opacity: 0;
            }
        }
    </style>
</div>
