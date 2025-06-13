<style>
    .chart-loading {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 300px;
        background-color: rgba(255, 255, 255, 0.7);
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 10;
    }
    
    .chart-spinner {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: 3px solid rgba(248, 184, 3, 0.2);
        border-top-color: #F8B803;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }
    
    .dashboard-stat-card {
        transition: all 0.3s ease;
    }
    
    .dashboard-stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
</style>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div wire:poll.15s="loadDashboardData" wire:loading.class.delay="opacity-50" class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-8">
        <div class="bg-[#F8B803] p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-[#1B1B18]">Hotel Management Dashboard</h1>
                    <p class="text-[#1B1B18] opacity-80">Welcome to the NovaERA Hotel Management System</p>
                </div>
                <div class="text-sm text-white bg-[#1B1B18] bg-opacity-20 py-1 px-3 rounded-full flex items-center animate-pulse" wire:loading>
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Auto-refreshing... (Last updated: {{ now()->format('H:i:s') }})</span>
                </div>
            </div>
            <div class="flex flex-wrap mt-4 -mx-2">
                <div class="px-2 w-1/4">
                    <div class="bg-white rounded-lg p-3 shadow-sm flex items-center dashboard-stat-card">
                        <div class="rounded-full bg-blue-500 p-2 mr-2">
                            <i class="fas fa-percentage text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Occupancy Rate</p>
                            <p class="font-bold text-lg">{{ $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100) : 0 }}%</p>
                        </div>
                    </div>
                </div>
                <div class="px-2 w-1/4">
                    <div class="bg-white rounded-lg p-3 shadow-sm flex items-center dashboard-stat-card">
                        <div class="rounded-full bg-green-500 p-2 mr-2">
                            <i class="fas fa-broom text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Clean Rate</p>
                            <p class="font-bold text-lg">{{ $totalRooms > 0 ? round(($cleanRooms / $totalRooms) * 100) : 0 }}%</p>
                        </div>
                    </div>
                </div>
                <div class="px-2 w-1/4">
                    <div class="bg-white rounded-lg p-3 shadow-sm flex items-center dashboard-stat-card">
                        <div class="rounded-full bg-purple-500 p-2 mr-2">
                            <i class="fas fa-calendar-check text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Today's Activity</p>
                            <p class="font-bold text-lg">{{ $checkedInToday + $checkedOutToday }}</p>
                        </div>
                    </div>
                </div>
                <div class="px-2 w-1/4">
                    <div class="bg-white rounded-lg p-3 shadow-sm flex items-center dashboard-stat-card">
                        <div class="rounded-full bg-teal-500 p-2 mr-2">
                            <i class="fas fa-utensils text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Food Orders</p>
                            <p class="font-bold text-lg">{{ $totalFoodOrders }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="p-6">
            <!-- Room Status Section -->
            <h2 class="text-xl font-semibold mb-4 text-[#1B1B18]">Room Status Overview</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold mb-1">Total Rooms</h3>
                            <p class="text-3xl font-bold">{{ $totalRooms ?? 0 }}</p>
                        </div>
                        <div class="rounded-full bg-white bg-opacity-30 p-3">
                            <i class="fas fa-bed text-2xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold mb-1">Available Rooms</h3>
                            <p class="text-3xl font-bold">{{ $availableRooms ?? 0 }}</p>
                            <p class="text-sm mt-1">{{ round(($availableRooms / $totalRooms) * 100) }}% of total</p>
                        </div>
                        <div class="rounded-full bg-white bg-opacity-30 p-3">
                            <i class="fas fa-check-circle text-2xl"></i>
                        </div>
                    </div>
                    <div class="mt-2 bg-white bg-opacity-20 rounded-full h-2">
                        <div class="bg-white h-2 rounded-full" style="width: {{ ($availableRooms / $totalRooms) * 100 }}%"></div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-lg shadow p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold mb-1">Occupied Rooms</h3>
                            <p class="text-3xl font-bold">{{ $occupiedRooms ?? 0 }}</p>
                            <p class="text-sm mt-1">{{ round(($occupiedRooms / $totalRooms) * 100) }}% of total</p>
                        </div>
                        <div class="rounded-full bg-white bg-opacity-30 p-3">
                            <i class="fas fa-user text-2xl"></i>
                        </div>
                    </div>
                    <div class="mt-2 bg-white bg-opacity-20 rounded-full h-2">
                        <div class="bg-white h-2 rounded-full" style="width: {{ ($occupiedRooms / $totalRooms) * 100 }}%"></div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-lg shadow p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold mb-1">Maintenance</h3>
                            <p class="text-3xl font-bold">{{ $maintenanceRooms ?? 0 }}</p>
                            <p class="text-sm mt-1">{{ round(($maintenanceRooms / $totalRooms) * 100) }}% of total</p>
                        </div>
                        <div class="rounded-full bg-white bg-opacity-30 p-3">
                            <i class="fas fa-tools text-2xl"></i>
                        </div>
                    </div>
                    <div class="mt-2 bg-white bg-opacity-20 rounded-full h-2">
                        <div class="bg-white h-2 rounded-full" style="width: {{ ($maintenanceRooms / $totalRooms) * 100 }}%"></div>
                    </div>
                </div>
            </div>
            
            <!-- Cleaning Status Section -->
            <h2 class="text-xl font-semibold mb-4 text-[#1B1B18]">Cleaning Status</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-gradient-to-r from-green-400 to-green-500 rounded-lg shadow p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold mb-1">Clean Rooms</h3>
                            <p class="text-3xl font-bold">{{ $cleanRooms ?? 0 }}</p>
                            <p class="text-sm mt-1">{{ round(($cleanRooms / $totalRooms) * 100) }}% of total</p>
                        </div>
                        <div class="rounded-full bg-white bg-opacity-30 p-3">
                            <i class="fas fa-broom text-2xl"></i>
                        </div>
                    </div>
                    <div class="mt-2 bg-white bg-opacity-20 rounded-full h-2">
                        <div class="bg-white h-2 rounded-full" style="width: {{ ($cleanRooms / $totalRooms) * 100 }}%"></div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-red-400 to-red-500 rounded-lg shadow p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold mb-1">Not Cleaned</h3>
                            <p class="text-3xl font-bold">{{ $notCleanedRooms ?? 0 }}</p>
                            <p class="text-sm mt-1">{{ round(($notCleanedRooms / $totalRooms) * 100) }}% of total</p>
                        </div>
                        <div class="rounded-full bg-white bg-opacity-30 p-3">
                            <i class="fas fa-times-circle text-2xl"></i>
                        </div>
                    </div>
                    <div class="mt-2 bg-white bg-opacity-20 rounded-full h-2">
                        <div class="bg-white h-2 rounded-full" style="width: {{ ($notCleanedRooms / $totalRooms) * 100 }}%"></div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-blue-400 to-blue-500 rounded-lg shadow p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold mb-1">Cleaning In Progress</h3>
                            <p class="text-3xl font-bold">{{ $inProgressCleaningRooms ?? 0 }}</p>
                            <p class="text-sm mt-1">{{ round(($inProgressCleaningRooms / $totalRooms) * 100) }}% of total</p>
                        </div>
                        <div class="rounded-full bg-white bg-opacity-30 p-3">
                            <i class="fas fa-sync text-2xl"></i>
                        </div>
                    </div>
                    <div class="mt-2 bg-white bg-opacity-20 rounded-full h-2">
                        <div class="bg-white h-2 rounded-full" style="width: {{ ($inProgressCleaningRooms / $totalRooms) * 100 }}%"></div>
                    </div>
                </div>
            </div>
            
            <!-- Check-in/Check-out Section -->
            <h2 class="text-xl font-semibold mb-4 text-[#1B1B18]">Check-in/Check-out Activity</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-lg shadow p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold mb-1">Today's Check-ins</h3>
                            <p class="text-3xl font-bold">{{ $checkedInToday ?? 0 }}</p>
                        </div>
                        <div class="rounded-full bg-white bg-opacity-30 p-3">
                            <i class="fas fa-sign-in-alt text-2xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg shadow p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold mb-1">Today's Check-outs</h3>
                            <p class="text-3xl font-bold">{{ $checkedOutToday ?? 0 }}</p>
                        </div>
                        <div class="rounded-full bg-white bg-opacity-30 p-3">
                            <i class="fas fa-sign-out-alt text-2xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-teal-500 to-teal-600 rounded-lg shadow p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold mb-1">Pending Check-outs</h3>
                            <p class="text-3xl font-bold">{{ $pendingCheckouts ?? 0 }}</p>
                        </div>
                        <div class="rounded-full bg-white bg-opacity-30 p-3">
                            <i class="fas fa-clock text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Food Service Section -->
            <h2 class="text-xl font-semibold mb-4 text-[#1B1B18] flex items-center">
                <i class="fas fa-utensils mr-2 text-[#F8B803]"></i> Food Service Statistics
            </h2>
            
            <!-- Modern Food Service Statistics Section -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        <div class="p-2 bg-amber-100 rounded-md mr-3">
                            <i class="fas fa-utensils text-amber-600"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Total Orders</h3>
                            <p class="text-gray-600 text-sm">All food service orders</p>
                        </div>
                    </div>
                    <div class="text-3xl font-bold text-gray-800">{{ $totalFoodOrders ?? 0 }}</div>
                </div>
                
                <!-- Order Status Progress Bar -->
                <div class="h-3 flex rounded-full overflow-hidden mb-3 bg-gray-200">
                    @php
                        $receivedPercent = $totalFoodOrders > 0 ? ($receivedOrders / $totalFoodOrders) * 100 : 0;
                        $preparingPercent = $totalFoodOrders > 0 ? ($preparingOrders / $totalFoodOrders) * 100 : 0;
                        $deliveredPercent = $totalFoodOrders > 0 ? ($deliveredOrders / $totalFoodOrders) * 100 : 0;
                    @endphp
                    <div class="bg-blue-500 h-full" style="width: {{ $receivedPercent }}%"></div>
                    <div class="bg-amber-500 h-full" style="width: {{ $preparingPercent }}%"></div>
                    <div class="bg-green-500 h-full" style="width: {{ $deliveredPercent }}%"></div>
                </div>
                
                <!-- Order Status Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="py-3 font-semibold text-gray-600">Status</th>
                                <th class="py-3 font-semibold text-gray-600">Count</th>
                                <th class="py-3 font-semibold text-gray-600">Percentage</th>
                                <th class="py-3 font-semibold text-gray-600">Trend</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b border-gray-100">
                                <td class="py-3">
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 rounded-full bg-blue-500 mr-2"></div>
                                        <span class="font-medium">Received</span>
                                    </div>
                                </td>
                                <td class="py-3 font-bold">{{ $receivedOrders }}</td>
                                <td class="py-3">{{ round($receivedPercent) }}%</td>
                                <td class="py-3">
                                    <div class="flex items-center">
                                        <div class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">New</div>
                                    </div>
                                </td>
                            </tr>
                            <tr class="border-b border-gray-100">
                                <td class="py-3">
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 rounded-full bg-amber-500 mr-2"></div>
                                        <span class="font-medium">Preparing</span>
                                    </div>
                                </td>
                                <td class="py-3 font-bold">{{ $preparingOrders }}</td>
                                <td class="py-3">{{ round($preparingPercent) }}%</td>
                                <td class="py-3">
                                    <div class="flex items-center">
                                        <div class="bg-amber-100 text-amber-800 px-2 py-1 rounded text-xs">In Process</div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="py-3">
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 rounded-full bg-green-500 mr-2"></div>
                                        <span class="font-medium">Delivered</span>
                                    </div>
                                </td>
                                <td class="py-3 font-bold">{{ $deliveredOrders }}</td>
                                <td class="py-3">{{ round($deliveredPercent) }}%</td>
                                <td class="py-3">
                                    <div class="flex items-center">
                                        <div class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Complete</div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Analytics & Insights Section -->
            <h2 class="text-xl font-semibold mb-4 text-[#1B1B18]">Analytics & Insights</h2>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Monthly Check-in/Check-out Trends</h3>
                    </div>
                    <div class="chart-container" style="height: 300px;">
                        <div wire:ignore>
                            <canvas id="monthlyActivityChart"></canvas>
                        </div>
                        <div class="chart-loading" wire:loading wire:target="loadDashboardData">
                            <div class="chart-spinner"></div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Room Category Distribution</h3>
                    </div>
                    <div class="chart-container" style="height: 300px;">
                        <div wire:ignore>
                            <canvas id="roomCategoryChart"></canvas>
                        </div>
                        <div class="chart-loading" wire:loading wire:target="loadDashboardData">
                            <div class="chart-spinner"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- System Health Section -->
                <div>
                    <h2 class="text-xl font-semibold mb-4 text-[#1B1B18]">System Health</h2>
                    <div class="grid grid-cols-1 md:grid-cols-1 gap-6 mb-4">
                        <div class="bg-white rounded-lg shadow overflow-hidden">
                            <div class="bg-teal-500 text-white px-4 py-2">
                                <h3 class="font-semibold">Server Status</h3>
                            </div>
                            <div class="p-4">
                                <div class="mb-4">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-sm text-gray-600">CPU Usage</span>
                                        <span class="text-sm font-medium text-gray-800">45%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-teal-500 h-2 rounded-full" style="width: 45%"></div>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-sm text-gray-600">Memory</span>
                                        <span class="text-sm font-medium text-gray-800">68%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-teal-500 h-2 rounded-full" style="width: 68%"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-sm text-gray-600">Disk Space</span>
                                        <span class="text-sm font-medium text-gray-800">32%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-teal-500 h-2 rounded-full" style="width: 32%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Activity Summary Section -->
                <div class="col-span-2">
                    <h2 class="text-xl font-semibold mb-4 text-[#1B1B18]">Activity Summary</h2>
                    <div class="grid grid-cols-1 gap-6 mb-8">
                        <div class="bg-white rounded-lg shadow overflow-hidden">
                            <div class="bg-purple-500 text-white px-4 py-2">
                                <h3 class="font-semibold">Recent Room Activities</h3>
                            </div>
                            <div class="p-4">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead>
                                            <tr>
                                                <th class="px-4 py-2 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Room</th>
                                                <th class="px-4 py-2 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Activity</th>
                                                <th class="px-4 py-2 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                                <th class="px-4 py-2 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            <!-- Sample activities - in a real app, these would come from a database -->
                                            <tr>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <div class="text-sm font-medium">Room 101</div>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <div class="text-sm">Check-in</div>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <div class="text-sm text-gray-500">{{ now()->subHours(1)->format('H:i') }}</div>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Complete</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <div class="text-sm font-medium">Room 205</div>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <div class="text-sm">Room Cleaned</div>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <div class="text-sm text-gray-500">{{ now()->subHours(2)->format('H:i') }}</div>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Complete</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <div class="text-sm font-medium">Room 310</div>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <div class="text-sm">Check-out</div>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <div class="text-sm text-gray-500">{{ now()->subHours(3)->format('H:i') }}</div>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Complete</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <div class="text-sm font-medium">Room 420</div>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <div class="text-sm">Maintenance</div>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <div class="text-sm text-gray-500">{{ now()->subHours(5)->format('H:i') }}</div>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">In Progress</span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Ensure charts are initialized properly on all page load scenarios
    function initChartsWithRetry() {
        // Try to initialize charts with a more robust approach
        if (typeof @this !== 'undefined' && @this.monthlyData && @this.roomsByCategory) {
            console.log('Data available, initializing charts');
            initializeCharts();
        } else {
            console.log('Data not immediately available, waiting...');
            // If data not available yet, retry after a short delay
            setTimeout(() => {
                if (typeof @this !== 'undefined' && @this.monthlyData && @this.roomsByCategory) {
                    console.log('Data available after delay, initializing charts');
                    initializeCharts();
                } else {
                    console.log('Data still not available, dispatching refreshData event');
                    // If still no data, try to reload the data
                    if (typeof @this !== 'undefined') {
                        @this.loadDashboardData();
                        
                        // One more attempt after data has been loaded
                        setTimeout(() => {
                            console.log('Final attempt to initialize charts');
                            initializeCharts();
                        }, 500);
                    }
                }
            }, 300);
        }
    }

    // Initialize charts when DOM is fully loaded
    document.addEventListener('DOMContentLoaded', () => {
        initChartsWithRetry();
    });

    // Handle Livewire initialization
    document.addEventListener('livewire:initialized', () => {
        initChartsWithRetry();
        
        // Listen for dashboard data updates
        @this.on('dashboard-data-updated', () => {
            // Reinitialize charts with new data
            initializeCharts();
        });
    });
    
    // Handle Livewire navigation (when coming from another page)
    document.addEventListener('livewire:navigated', () => {
        setTimeout(() => {
            initChartsWithRetry();
        }, 100);
    });
    
    function initializeCharts() {
        try {
            console.log('Initializing charts with data:', @this.monthlyData, @this.roomsByCategory);
            
            // Initialize Monthly Activity Chart
            const monthlyCtx = document.getElementById('monthlyActivityChart');
            if (monthlyCtx) {
                // Clear any existing chart
                if (monthlyCtx._chart) {
                    monthlyCtx._chart.destroy();
                }
                
                // Safely access data with fallbacks
                const monthlyData = @this.monthlyData || [];
                
                if (!monthlyData || monthlyData.length === 0) {
                    console.warn('Monthly data is not available yet');
                    return; // Exit if no data
                }
            
            monthlyCtx._chart = new Chart(monthlyCtx, {
                type: 'line',
                data: {
                    labels: monthlyData.map(data => data.month),
                    datasets: [
                        {
                            label: 'Check-ins',
                            data: monthlyData.map(data => data.checkins),
                            backgroundColor: 'rgba(59, 130, 246, 0.2)',
                            borderColor: 'rgb(59, 130, 246)',
                            borderWidth: 2,
                            tension: 0.3
                        },
                        {
                            label: 'Check-outs',
                            data: monthlyData.map(data => data.checkouts),
                            backgroundColor: 'rgba(239, 68, 68, 0.2)',
                            borderColor: 'rgb(239, 68, 68)',
                            borderWidth: 2,
                            tension: 0.3
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    layout: {
                        padding: {
                            left: 10,
                            right: 10,
                            top: 10,
                            bottom: 10
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 15,
                                padding: 15
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(27, 27, 24, 0.9)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            borderColor: '#F8B803',
                            borderWidth: 1,
                            padding: 10,
                            displayColors: true
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }
        
        // Initialize Room Category Chart
        const categoryCtx = document.getElementById('roomCategoryChart');
        if (categoryCtx) {
            // Clear any existing chart
            if (categoryCtx._chart) {
                categoryCtx._chart.destroy();
            }
            
            // Safely access data with fallbacks
            const roomsByCategory = @this.roomsByCategory || [];
            
            if (!roomsByCategory || roomsByCategory.length === 0) {
                console.warn('Room category data is not available yet');
                return; // Exit if no data
            }
            
            const backgroundColors = [
                'rgba(59, 130, 246, 0.7)', // Blue
                'rgba(16, 185, 129, 0.7)', // Green
                'rgba(245, 158, 11, 0.7)', // Yellow
                'rgba(239, 68, 68, 0.7)',  // Red
                'rgba(139, 92, 246, 0.7)',  // Purple
                'rgba(236, 72, 153, 0.7)'  // Pink
            ];
            
            categoryCtx._chart = new Chart(categoryCtx, {
                type: 'doughnut',
                data: {
                    labels: roomsByCategory.map(data => data.category),
                    datasets: [{
                        data: roomsByCategory.map(data => data.count),
                        backgroundColor: backgroundColors.slice(0, roomsByCategory.length),
                        borderColor: '#ffffff',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                        }
                    }
                }
            });
        }
        } catch (error) {
            console.error('Error initializing charts:', error);
            // If there was an error, try to reinitialize after a short delay
            setTimeout(() => {
                console.log('Attempting to initialize charts again after error');
                if (typeof @this !== 'undefined') {
                    @this.loadDashboardData();
                }
            }, 1000);
        }
    }
</script>
