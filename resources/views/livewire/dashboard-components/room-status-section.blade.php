<!-- Room Status Section -->
<div class="bg-white rounded-lg shadow-lg overflow-hidden mb-8">
    <div class="p-4 bg-[#f9b903] text-[#1B1B18]">
        <h2 class="text-xl font-semibold">Room Status Overview</h2>
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
                    <p class="text-2xl font-bold text-gray-800">{{ $availableRooms ?? 0 }}</p>
                </div>
                <div class="relative pt-1">
                    <div class="flex items-center justify-between mb-1">
                        <div>
                            <span class="text-xs font-semibold inline-block text-green-600">
                                {{ $totalRooms > 0 ? round(($availableRooms / $totalRooms) * 100) : 0 }}% of rooms
                            </span>
                        </div>
                    </div>
                    <div class="overflow-hidden h-2 mb-1 text-xs flex rounded bg-green-100">
                        <div style="width: {{ $totalRooms > 0 ? ($availableRooms / $totalRooms) * 100 : 0 }}%" class="animate-bar shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-green-500"></div>
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
                    <p class="text-2xl font-bold text-gray-800">{{ $occupiedRooms ?? 0 }}</p>
                </div>
                <div class="relative pt-1">
                    <div class="flex items-center justify-between mb-1">
                        <div>
                            <span class="text-xs font-semibold inline-block text-blue-600">
                                {{ $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100) : 0 }}% of rooms
                            </span>
                        </div>
                    </div>
                    <div class="overflow-hidden h-2 mb-1 text-xs flex rounded bg-blue-100">
                        <div style="width: {{ $totalRooms > 0 ? ($occupiedRooms / $totalRooms) * 100 : 0 }}%" class="animate-bar shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-blue-500"></div>
                    </div>
                </div>
            </div>
            
            <!-- Maintenance Rooms -->
            <div class="bg-white border border-gray-100 rounded-lg shadow-sm p-4 fade-in-up">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="font-semibold text-gray-700">Maintenance</h3>
                    <span class="rounded-full bg-amber-100 text-amber-700 px-2 py-0.5 text-xs">Unavailable</span>
                </div>
                <div class="flex items-center mb-2">
                    <div class="rounded-full bg-amber-500 p-2 mr-2">
                        <i class="fas fa-tools text-white text-sm"></i>
                    </div>
                    <p class="text-2xl font-bold text-gray-800">{{ $maintenanceRooms ?? 0 }}</p>
                </div>
                <div class="relative pt-1">
                    <div class="flex items-center justify-between mb-1">
                        <div>
                            <span class="text-xs font-semibold inline-block text-amber-600">
                                {{ $totalRooms > 0 ? round(($maintenanceRooms / $totalRooms) * 100) : 0 }}% of rooms
                            </span>
                        </div>
                    </div>
                    <div class="overflow-hidden h-2 mb-1 text-xs flex rounded bg-amber-100">
                        <div style="width: {{ $totalRooms > 0 ? ($maintenanceRooms / $totalRooms) * 100 : 0 }}%" class="animate-bar shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-amber-500"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
