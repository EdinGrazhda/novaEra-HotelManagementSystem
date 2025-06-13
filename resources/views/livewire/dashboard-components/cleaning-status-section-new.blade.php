<!-- Cleaning Status Section -->
<div class="bg-white rounded-lg shadow-lg overflow-hidden">
    <div class="p-4 bg-[#f9b903] text-[#1B1B18] flex items-center justify-between">
        <h2 class="text-xl font-semibold">Housekeeping Status</h2>
        <div class="rounded-full bg-white/70 px-2 py-1 text-xs flex items-center">
            <i class="fas fa-sync-alt mr-1"></i> Updated {{ now()->format('H:i') }}
        </div>
    </div>
    <div class="p-6">
        <!-- Cleaning Status Gauge -->
        <div class="flex flex-col items-center justify-center mb-8">
            <div class="relative w-40 h-40">
                <!-- Background circle -->
                <svg class="w-full h-full" viewBox="0 0 36 36">
                    <!-- Gray background track -->
                    <path
                        d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                        stroke="#f1f1f1"
                        stroke-width="4"
                        fill="none"
                    />
                    <!-- Clean percentage arc with gradient -->
                    <path
                        d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                        stroke="url(#cleanGradient)"
                        stroke-width="4"
                        fill="none"
                        stroke-dasharray="{{ $totalRooms > 0 ? ($cleanRooms / $totalRooms) * 100 : 0 }}, 100"
                        stroke-linecap="round"
                    />
                    <!-- SVG Gradient definition -->
                    <defs>
                        <linearGradient id="cleanGradient" x1="0%" y1="0%" x2="100%" y2="0%">
                            <stop offset="0%" stop-color="#10B981" />
                            <stop offset="100%" stop-color="#34D399" />
                        </linearGradient>
                    </defs>
                    
                    <!-- White center circle for better visuals -->
                    <circle cx="18" cy="18" r="12" fill="white" />
                    
                    <!-- Number display -->
                    <text x="18" y="17.5" text-anchor="middle" fill="#333" font-size="7.5" font-weight="700">
                        {{ $totalRooms > 0 ? round(($cleanRooms / $totalRooms) * 100) : 0 }}
                    </text>
                    <!-- Percentage symbol -->
                    <text x="18" y="22.5" text-anchor="middle" fill="#666" font-size="3.5" font-weight="500" letter-spacing="0.3">
                        CLEAN %
                    </text>
                </svg>
            </div>
            <p class="text-gray-500 mt-2">Clean Rooms Percentage</p>
            
            <!-- Simple legend -->
            <div class="flex items-center mt-1 space-x-3 text-xs text-gray-500">
                <span class="flex items-center">
                    <span class="inline-block w-2 h-2 rounded-full bg-[#10B981] mr-1"></span> Clean
                </span>
                <span class="flex items-center">
                    <span class="inline-block w-2 h-2 rounded-full bg-amber-500 mr-1"></span> In Progress
                </span>
                <span class="flex items-center">
                    <span class="inline-block w-2 h-2 rounded-full bg-red-500 mr-1"></span> Needs Cleaning
                </span>
            </div>
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
</div>
