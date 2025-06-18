{{-- <!-- Cleaning Status Section -->
<div class="bg-white rounded-lg shadow-lg overflow-hidden">
    <div class="p-4 bg-[#f9b903] text-[#1B1B18]">
        <h2 class="text-xl font-semibold">Housekeeping Status</h2>
    </div>
    <div class="p-6">
        <!-- Cleaning Status Gauge -->
        <div class="flex flex-col items-center justify-center mb-8">            <div class="relative w-40 h-40">
                <!-- Background circle -->
                <svg class="w-full h-full" viewBox="0 0 36 36">
                    <path
                        d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                        stroke="#eee"
                        stroke-width="3"
                        fill="none"
                    />
                    <!-- Clean percentage arc -->
                    <path
                        d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                        stroke="#10B981"
                        stroke-width="3"
                        fill="none"
                        stroke-dasharray="{{ $totalRooms > 0 ? ($cleanRooms / $totalRooms) * 100 : 0 }}, 100"
                        stroke-linecap="round"
                    />
                    <!-- Number display -->
                    <text x="18" y="18" text-anchor="middle" fill="#333" font-size="9" font-weight="600">
                        {{ $totalRooms > 0 ? round(($cleanRooms / $totalRooms) * 100) : 0 }}
                    </text>
                    <!-- Percentage symbol -->
                    <text x="18" y="23" text-anchor="middle" fill="#333" font-size="4" font-weight="400">
                        % CLEAN
                    </text>
                </svg>
            </div>
            <p class="text-gray-500 mt-2">Clean Rooms Percentage</p>
        </div>
        
        <!-- Cleaning Status Details -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <!-- Clean -->
            <div class="bg-white border border-gray-100 rounded-lg p-4 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 right-0 h-full w-1 bg-green-500"></div>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Clean</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $cleanRooms ?? 0 }}</p>
                    </div>
                    <div class="rounded-full bg-green-100 p-2 text-green-500">
                        <i class="fas fa-check-circle text-lg"></i>
                    </div>
                </div>
                <div class="mt-4 text-xs text-green-600">
                    {{ $totalRooms > 0 ? round(($cleanRooms / $totalRooms) * 100) : 0 }}% of total rooms
                </div>
            </div>
            
            <!-- In Progress -->
            <div class="bg-white border border-gray-100 rounded-lg p-4 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 right-0 h-full w-1 bg-amber-500"></div>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">In Progress</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $inProgressCleaningRooms ?? 0 }}</p>
                    </div>
                    <div class="rounded-full bg-amber-100 p-2 text-amber-500">
                        <i class="fas fa-broom text-lg"></i>
                    </div>
                </div>
                <div class="mt-4 text-xs text-amber-600">
                    {{ $totalRooms > 0 ? round(($inProgressCleaningRooms / $totalRooms) * 100) : 0 }}% of total rooms
                </div>
            </div>
            
            <!-- Needs Cleaning -->
            <div class="bg-white border border-gray-100 rounded-lg p-4 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 right-0 h-full w-1 bg-red-500"></div>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Needs Cleaning</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $notCleanedRooms ?? 0 }}</p>
                    </div>
                    <div class="rounded-full bg-red-100 p-2 text-red-500">
                        <i class="fas fa-exclamation-triangle text-lg"></i>
                    </div>
                </div>
                <div class="mt-4 text-xs text-red-600">
                    {{ $totalRooms > 0 ? round(($notCleanedRooms / $totalRooms) * 100) : 0 }}% of total rooms
                </div>
            </div>
        </div>
    </div>
</div> --}}
