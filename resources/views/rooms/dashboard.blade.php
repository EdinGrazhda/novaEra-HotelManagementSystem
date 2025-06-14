<x-layouts.app :title="__('Room Management')">
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
    }
</style>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-8">
        <div class="bg-[#F8B803] p-6">
            <h1 class="text-2xl font-semibold text-[#1B1B18]">Room Management Dashboard</h1>
            <p class="text-[#1B1B18] opacity-80">Welcome to the NovaERA Hotel Management System</p>
            <div class="flex flex-wrap mt-4 -mx-2">
                <div class="px-2 w-1/4">
                    <div class="bg-white rounded-lg p-3 shadow-sm flex items-center">
                        <div class="rounded-full bg-blue-500 p-2 mr-2">
                            <i class="fas fa-percentage text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Occupancy Rate</p>
                            <p class="font-bold text-lg">{{ round(($occupiedRooms / $totalRooms) * 100) }}%</p>
                        </div>
                    </div>
                </div>
                <div class="px-2 w-1/4">
                    <div class="bg-white rounded-lg p-3 shadow-sm flex items-center">
                        <div class="rounded-full bg-green-500 p-2 mr-2">
                            <i class="fas fa-broom text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Clean Rate</p>
                            <p class="font-bold text-lg">{{ round(($cleanRooms / $totalRooms) * 100) }}%</p>
                        </div>
                    </div>
                </div>
                <div class="px-2 w-1/4">
                    <div class="bg-white rounded-lg p-3 shadow-sm flex items-center">
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
                    <div class="bg-white rounded-lg p-3 shadow-sm flex items-center">
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
                
                <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg shadow p-6 text-white">
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
            
            <h2 class="text-xl font-semibold mb-4 text-[#1B1B18]">Food Service</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white border border-gray-200 rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-[#1B1B18]">Total Food Orders</h3>
                        <div class="rounded-full bg-teal-100 p-3">
                            <i class="fas fa-utensils text-teal-500 text-xl"></i>
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-teal-600">{{ $totalFoodOrders ?? 0 }}</p>
                    <div class="mt-4 flex items-center text-sm text-gray-500">
                        <span class="mr-1">Today:</span>
                        <span class="font-semibold text-[#1B1B18]">{{ $totalFoodOrders ?? 0 }}</span>
                        @if($activeOrders > 0)
                        <span class="ml-auto flex items-center text-yellow-500">
                            <i class="fas fa-circle text-xs mr-1"></i>
                            {{ $activeOrders }} pending
                        </span>
                        @endif
                    </div>
                    <div class="mt-2 bg-gray-200 rounded-full h-1.5">
                        <div class="bg-teal-500 h-1.5 rounded-full" style="width: {{ $totalFoodOrders > 0 ? ($deliveredOrders / $totalFoodOrders) * 100 : 0 }}%"></div>
                    </div>
                </div>
                
                <div class="bg-white border border-gray-200 rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-[#1B1B18]">Active Orders</h3>
                        <div class="rounded-full bg-cyan-100 p-3">
                            <i class="fas fa-spinner text-cyan-500 text-xl"></i>
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-cyan-600">{{ $activeOrders ?? 0 }}</p>
                    <div class="mt-4 flex items-center text-sm text-gray-500">
                        @if($totalFoodOrders > 0)
                        <span class="font-semibold text-[#1B1B18]">{{ round(($activeOrders / $totalFoodOrders) * 100) }}%</span>
                        <span class="ml-1">of total orders</span>
                        @endif
                        <span class="ml-auto flex items-center">
                            <i class="fas fa-clock text-xs mr-1 text-cyan-500"></i>
                            In progress
                        </span>
                    </div>
                    
                    <div class="mt-3 grid grid-cols-3 gap-2">
                        <div class="text-center p-2 bg-yellow-50 rounded-lg">
                            <i class="fas fa-hourglass-start text-yellow-500 mb-1"></i>
                            <p class="text-xs font-medium">New</p>
                            <p class="font-semibold">{{ $activeOrders > 0 ? round($activeOrders / 3) : 0 }}</p>
                        </div>
                        <div class="text-center p-2 bg-orange-50 rounded-lg">
                            <i class="fas fa-fire text-orange-500 mb-1"></i>
                            <p class="text-xs font-medium">Cooking</p>
                            <p class="font-semibold">{{ $activeOrders > 0 ? round($activeOrders / 3) : 0 }}</p>
                        </div>
                        <div class="text-center p-2 bg-blue-50 rounded-lg">
                            <i class="fas fa-route text-blue-500 mb-1"></i>
                            <p class="text-xs font-medium">Delivery</p>
                            <p class="font-semibold">{{ $activeOrders > 0 ? round($activeOrders / 3) : 0 }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white border border-gray-200 rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-[#1B1B18]">Delivered Orders</h3>
                        <div class="rounded-full bg-green-100 p-3">
                            <i class="fas fa-check text-green-500 text-xl"></i>
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-green-600">{{ $deliveredOrders ?? 0 }}</p>
                    <div class="mt-4 flex items-center text-sm text-gray-500">
                        @if($totalFoodOrders > 0)
                        <span class="font-semibold text-[#1B1B18]">{{ round(($deliveredOrders / $totalFoodOrders) * 100) }}%</span>
                        <span class="ml-1">completion rate</span>
                        @endif
                        <span class="ml-auto flex items-center">
                            <i class="fas fa-check-circle text-xs mr-1 text-green-500"></i>
                            Completed
                        </span>
                    </div>
                    <div class="mt-2 bg-gray-200 rounded-full h-1.5">
                        <div class="bg-green-500 h-1.5 rounded-full" style="width: {{ $totalFoodOrders > 0 ? ($deliveredOrders / $totalFoodOrders) * 100 : 0 }}%"></div>
                    </div>
                </div>
            </div>
            
            <h2 class="text-xl font-semibold mb-4 text-[#1B1B18]">Analytics & Insights</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Monthly Check-in/Check-out Chart -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-[#1B1B18]">Monthly Check-in/Check-out Trends</h3>
                        <div class="text-sm text-gray-500">Last 6 Months</div>
                    </div>
                    <div class="chart-container">
                        <div class="chart-loading">
                            <div class="chart-spinner"></div>
                        </div>
                        <canvas id="monthlyActivityChart"></canvas>
                    </div>
                </div>
                
                <!-- Room Category Distribution Chart -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-[#1B1B18]">Room Category Distribution</h3>
                        <div class="text-sm text-gray-500">Current Status</div>
                    </div>
                    <div class="chart-container">
                        <div class="chart-loading">
                            <div class="chart-spinner"></div>
                        </div>
                        <canvas id="roomCategoryChart"></canvas>
                    </div>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Latest Activity Card -->
                <div class="bg-white rounded-lg shadow p-6 col-span-1">
                    <h3 class="text-lg font-semibold mb-4 text-[#1B1B18] flex items-center">
                        <i class="fas fa-clock mr-2 text-[#F8B803]"></i> Latest Activity
                    </h3>
                    <div class="space-y-4">
                        @if($checkedInToday > 0)
                        <div class="flex items-center p-3 bg-blue-50 rounded-lg">
                            <div class="rounded-full bg-blue-100 p-2 mr-3">
                                <i class="fas fa-sign-in-alt text-blue-500"></i>
                            </div>
                            <div>
                                <p class="font-medium">{{ $checkedInToday }} Check-in{{ $checkedInToday > 1 ? 's' : '' }}</p>
                                <p class="text-sm text-gray-500">Today</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($checkedOutToday > 0)
                        <div class="flex items-center p-3 bg-purple-50 rounded-lg">
                            <div class="rounded-full bg-purple-100 p-2 mr-3">
                                <i class="fas fa-sign-out-alt text-purple-500"></i>
                            </div>
                            <div>
                                <p class="font-medium">{{ $checkedOutToday }} Check-out{{ $checkedOutToday > 1 ? 's' : '' }}</p>
                                <p class="text-sm text-gray-500">Today</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($pendingCheckouts > 0)
                        <div class="flex items-center p-3 bg-orange-50 rounded-lg">
                            <div class="rounded-full bg-orange-100 p-2 mr-3">
                                <i class="fas fa-hourglass-half text-orange-500"></i>
                            </div>
                            <div>
                                <p class="font-medium">{{ $pendingCheckouts }} Pending Check-out{{ $pendingCheckouts > 1 ? 's' : '' }}</p>
                                <p class="text-sm text-gray-500">Awaiting departure</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($notCleanedRooms > 0)
                        <div class="flex items-center p-3 bg-red-50 rounded-lg">
                            <div class="rounded-full bg-red-100 p-2 mr-3">
                                <i class="fas fa-broom text-red-500"></i>
                            </div>
                            <div>
                                <p class="font-medium">{{ $notCleanedRooms }} Room{{ $notCleanedRooms > 1 ? 's' : '' }} Need Cleaning</p>
                                <p class="text-sm text-gray-500">Awaiting housekeeping</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                
                <!-- Room Status Summary Table -->
                <div class="bg-white rounded-lg shadow p-6 col-span-2">
                    <h3 class="text-lg font-semibold mb-4 text-[#1B1B18] flex items-center">
                        <i class="fas fa-clipboard-list mr-2 text-[#F8B803]"></i> Room Status Overview
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr>
                                    <th class="pb-3 font-semibold text-gray-600">Status</th>
                                    <th class="pb-3 font-semibold text-gray-600">Count</th>
                                    <th class="pb-3 font-semibold text-gray-600">Percentage</th>
                                    <th class="pb-3 font-semibold text-gray-600">Indicator</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-t border-gray-100">
                                    <td class="py-2">
                                        <div class="flex items-center">
                                            <div class="w-3 h-3 rounded-full bg-green-500 mr-2"></div>
                                            <span>Available</span>
                                        </div>
                                    </td>
                                    <td class="py-2">{{ $availableRooms }}</td>
                                    <td class="py-2">{{ round(($availableRooms / $totalRooms) * 100) }}%</td>
                                    <td class="py-2 w-1/4">
                                        <div class="bg-gray-200 rounded-full h-2.5">
                                            <div class="bg-green-500 h-2.5 rounded-full" style="width: {{ ($availableRooms / $totalRooms) * 100 }}%"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="border-t border-gray-100">
                                    <td class="py-2">
                                        <div class="flex items-center">
                                            <div class="w-3 h-3 rounded-full bg-red-500 mr-2"></div>
                                            <span>Occupied</span>
                                        </div>
                                    </td>
                                    <td class="py-2">{{ $occupiedRooms }}</td>
                                    <td class="py-2">{{ round(($occupiedRooms / $totalRooms) * 100) }}%</td>
                                    <td class="py-2 w-1/4">
                                        <div class="bg-gray-200 rounded-full h-2.5">
                                            <div class="bg-red-500 h-2.5 rounded-full" style="width: {{ ($occupiedRooms / $totalRooms) * 100 }}%"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="border-t border-gray-100">
                                    <td class="py-2">
                                        <div class="flex items-center">
                                            <div class="w-3 h-3 rounded-full bg-yellow-500 mr-2"></div>
                                            <span>Maintenance</span>
                                        </div>
                                    </td>
                                    <td class="py-2">{{ $maintenanceRooms }}</td>
                                    <td class="py-2">{{ round(($maintenanceRooms / $totalRooms) * 100) }}%</td>
                                    <td class="py-2 w-1/4">
                                        <div class="bg-gray-200 rounded-full h-2.5">
                                            <div class="bg-yellow-500 h-2.5 rounded-full" style="width: {{ ($maintenanceRooms / $totalRooms) * 100 }}%"></div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- System Health Card -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold mb-4 text-[#1B1B18]">System Health</h2>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="rounded-full bg-green-100 p-2 mr-2">
                                    <i class="fas fa-server text-green-500"></i>
                                </div>
                                <span>System Status</span>
                            </div>
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">Operational</span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="rounded-full bg-green-100 p-2 mr-2">
                                    <i class="fas fa-database text-green-500"></i>
                                </div>
                                <span>Database</span>
                            </div>
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">Connected</span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="rounded-full bg-green-100 p-2 mr-2">
                                    <i class="fas fa-calendar-check text-green-500"></i>
                                </div>
                                <span>Last Update</span>
                            </div>
                            <span class="text-sm text-gray-600">{{ now()->format('d M Y, H:i') }}</span>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Actions Card -->
                <div class="bg-white rounded-lg shadow p-6 col-span-2">
                    <h2 class="text-xl font-semibold mb-4 text-[#1B1B18]">Quick Actions</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <a href="{{ route('rooms.create') }}" class="flex items-center p-4 bg-gray-50 rounded-lg border border-gray-200 hover:bg-blue-50 hover:border-blue-200 transition-colors group">
                            <div class="rounded-full bg-[#F8B803] bg-opacity-20 p-3 mr-3 group-hover:bg-[#F8B803] group-hover:bg-opacity-30">
                                <i class="fas fa-plus text-[#F8B803]"></i>
                            </div>
                            <div>
                                <h3 class="font-medium text-[#1B1B18]">Add New Room</h3>
                                <p class="text-sm text-gray-500">Create a new room</p>
                            </div>
                        </a>
                        
                        <a href="{{ route('rooms.index') }}" class="flex items-center p-4 bg-gray-50 rounded-lg border border-gray-200 hover:bg-blue-50 hover:border-blue-200 transition-colors group">
                            <div class="rounded-full bg-blue-500 bg-opacity-20 p-3 mr-3 group-hover:bg-blue-500 group-hover:bg-opacity-30">
                                <i class="fas fa-list text-blue-500"></i>
                            </div>
                            <div>
                                <h3 class="font-medium text-[#1B1B18]">View All Rooms</h3>
                                <p class="text-sm text-gray-500">Manage your rooms</p>
                            </div>
                        </a>
                        
                        <a href="{{ route('calendar.index') }}" class="flex items-center p-4 bg-gray-50 rounded-lg border border-gray-200 hover:bg-blue-50 hover:border-blue-200 transition-colors group">
                            <div class="rounded-full bg-green-500 bg-opacity-20 p-3 mr-3 group-hover:bg-green-500 group-hover:bg-opacity-30">
                                <i class="fas fa-calendar-alt text-green-500"></i>
                            </div>
                            <div>
                                <h3 class="font-medium text-[#1B1B18]">Room Calendar</h3>
                                <p class="text-sm text-gray-500">View room availability</p>
                            </div>
                        </a>
                        
                        <a href="#" class="flex items-center p-4 bg-gray-50 rounded-lg border border-gray-200 hover:bg-blue-50 hover:border-blue-200 transition-colors group">
                            <div class="rounded-full bg-purple-500 bg-opacity-20 p-3 mr-3 group-hover:bg-purple-500 group-hover:bg-opacity-30">
                                <i class="fas fa-cog text-purple-500"></i>
                            </div>
                            <div>
                                <h3 class="font-medium text-[#1B1B18]">Settings</h3>
                                <p class="text-sm text-gray-500">Configure system</p>
                            </div>
                        </a>
                    </div>
                    
                    <div class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-100">
                        <div class="flex items-start">
                            <div class="rounded-full bg-blue-100 p-2 mr-3">
                                <i class="fas fa-info-circle text-blue-500"></i>
                            </div>
                            <div>
                                <h3 class="font-medium text-[#1B1B18] mb-1">Need Help?</h3>
                                <p class="text-sm text-gray-600">Visit our <a href="#" class="text-blue-600 hover:underline">documentation center</a> for guides and tutorials on managing the hotel system.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Function to initialize charts
    function initializeCharts() {
        // Hide loading indicators
        document.querySelectorAll('.chart-loading').forEach(loader => {
            setTimeout(() => {
                loader.style.display = 'none';
            }, 500); // Small delay to ensure charts are rendered
        });
        
        // Monthly Check-in/Check-out Chart
        initMonthlyChart();
        
        // Room Category Distribution Chart
        initCategoryChart();
    }
    
    function initMonthlyChart() {
        const monthlyChartElement = document.getElementById('monthlyActivityChart');
        if (!monthlyChartElement) return;
        
        // Clean up any existing chart
        if (monthlyChartElement._chart) {
            monthlyChartElement._chart.destroy();
        }
        
        const monthlyData = @json($monthlyData ?? []);
        const monthlyLabels = monthlyData.map(item => item.month || '');
        const checkInData = monthlyData.map(item => item.checkins || 0);
        const checkOutData = monthlyData.map(item => item.checkouts || 0);
        
        monthlyChartElement._chart = new Chart(monthlyChartElement, {
            type: 'line',
            data: {
                labels: monthlyLabels,
                datasets: [
                    {
                        label: 'Check-ins',
                        data: checkInData,
                        borderColor: '#4F46E5',
                        backgroundColor: 'rgba(79, 70, 229, 0.2)',
                        tension: 0.3,
                        fill: true
                    },
                    {
                        label: 'Check-outs',
                        data: checkOutData,
                        borderColor: '#EC4899',
                        backgroundColor: 'rgba(236, 72, 153, 0.2)',
                        tension: 0.3,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    }
    
    function initCategoryChart() {
        const categoryChartElement = document.getElementById('roomCategoryChart');
        if (!categoryChartElement) return;
        
        // Clean up any existing chart
        if (categoryChartElement._chart) {
            categoryChartElement._chart.destroy();
        }
        
        const categoryData = @json($roomsByCategory ?? []);
        const categoryLabels = categoryData.map(item => item.category || 'Uncategorized');
        const categoryValues = categoryData.map(item => item.count || 0);
        const backgroundColors = [
            'rgba(54, 162, 235, 0.8)',
            'rgba(255, 99, 132, 0.8)',
            'rgba(255, 206, 86, 0.8)',
            'rgba(75, 192, 192, 0.8)',
            'rgba(153, 102, 255, 0.8)',
            'rgba(255, 159, 64, 0.8)',
            'rgba(199, 199, 199, 0.8)'
        ];
        
        categoryChartElement._chart = new Chart(categoryChartElement, {
            type: 'doughnut',
            data: {
                labels: categoryLabels,
                datasets: [{
                    data: categoryValues,
                    backgroundColor: backgroundColors.slice(0, categoryLabels.length),
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
    
    // Ensure charts are initialized immediately when DOM is ready
    document.addEventListener('DOMContentLoaded', initializeCharts);
    
    // Also reinitialize if any Livewire updates happen (if applicable)
    document.addEventListener('livewire:load', initializeCharts);
    
    // Initialize on page load to ensure immediate rendering without waiting for DOMContentLoaded
    if (document.readyState === 'complete' || document.readyState === 'interactive') {
        setTimeout(initializeCharts, 100);
    }
    
    // For extra reliability, retry initialization if charts not visible
    setTimeout(function() {
        const monthlyChart = document.getElementById('monthlyActivityChart');
        const categoryChart = document.getElementById('roomCategoryChart');
        
        if ((!monthlyChart._chart || !categoryChart._chart) && document.querySelectorAll('.chart-loading').length > 0) {
            console.log("Retrying chart initialization...");
            initializeCharts();
        }
    }, 1000);
</script>

</x-layouts.app>
