<!-- Food Status Section -->
<div class="bg-white rounded-lg shadow-lg overflow-hidden mb-8">
    <div class="p-4 bg-[#f9b903] text-[#1B1B18]">
        <h2 class="text-xl font-semibold">Restaurant & Food Services</h2>
    </div>
    <div class="p-6">
        <!-- Orders Overview -->
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8">
            <div class="flex items-center mb-4 md:mb-0">
                <div class="rounded-full bg-[#f9b903] p-3 mr-3">
                    <i class="fas fa-utensils text-white text-lg"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Orders Today</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $totalFoodOrders ?? 0 }}</p>
                </div>
            </div>
            
            <!-- Order Fulfillment Rate -->
            <div class="bg-gray-50 rounded-lg p-3">
                <div class="flex items-center">
                    <div class="text-right mr-3">
                        <p class="text-sm text-gray-500">Order Fulfillment</p>
                        <p class="font-bold text-lg text-gray-800">{{ $totalFoodOrders > 0 ? round(($deliveredOrders / $totalFoodOrders) * 100) : 0 }}%</p>
                    </div>
                    <div class="rounded-full {{ $totalFoodOrders > 0 && ($deliveredOrders / $totalFoodOrders) * 100 > 75 ? 'bg-green-500' : 'bg-amber-500' }} p-2">
                        <i class="fas fa-chart-pie text-white text-sm"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Order Processing Steps -->
        <div class="mb-8">
            <h3 class="font-semibold text-gray-700 mb-4">Order Processing Pipeline</h3>
            
            <div class="relative">
                <!-- Order Pipeline Timeline -->
                <div class="absolute left-5 inset-y-0 w-0.5 bg-gray-200"></div>
                
                <!-- Received Orders -->
                <div class="relative flex items-center mb-8">
                    <div class="absolute flex items-center justify-center w-10 h-10 rounded-full border-4 border-white bg-blue-100 shadow-sm">
                        <i class="fas fa-clipboard-list text-blue-600"></i>
                    </div>
                    <div class="pl-16">
                        <h4 class="font-semibold text-gray-700">Received</h4>
                        <div class="flex items-end">
                            <span class="text-3xl font-bold text-blue-600 mr-2">{{ $receivedOrders ?? 0 }}</span>
                            <span class="text-gray-500 text-sm">orders</span>
                        </div>
                        <div class="mt-2 w-full bg-gray-200 rounded-full h-1.5">
                            <div class="bg-blue-600 animate-bar h-1.5 rounded-full" style="width: {{ $totalFoodOrders > 0 ? ($receivedOrders / $totalFoodOrders) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Preparing Orders -->
                <div class="relative flex items-center mb-8">
                    <div class="absolute flex items-center justify-center w-10 h-10 rounded-full border-4 border-white bg-amber-100 shadow-sm">
                        <i class="fas fa-fire text-amber-600"></i>
                    </div>
                    <div class="pl-16">
                        <h4 class="font-semibold text-gray-700">Preparing</h4>
                        <div class="flex items-end">
                            <span class="text-3xl font-bold text-amber-600 mr-2">{{ $preparingOrders ?? 0 }}</span>
                            <span class="text-gray-500 text-sm">orders</span>
                        </div>
                        <div class="mt-2 w-full bg-gray-200 rounded-full h-1.5">
                            <div class="bg-amber-500 animate-bar h-1.5 rounded-full" style="width: {{ $totalFoodOrders > 0 ? ($preparingOrders / $totalFoodOrders) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Delivered Orders -->
                <div class="relative flex items-center">
                    <div class="absolute flex items-center justify-center w-10 h-10 rounded-full border-4 border-white bg-green-100 shadow-sm">
                        <i class="fas fa-check-circle text-green-600"></i>
                    </div>
                    <div class="pl-16">
                        <h4 class="font-semibold text-gray-700">Delivered</h4>
                        <div class="flex items-end">
                            <span class="text-3xl font-bold text-green-600 mr-2">{{ $deliveredOrders ?? 0 }}</span>
                            <span class="text-gray-500 text-sm">orders</span>
                        </div>
                        <div class="mt-2 w-full bg-gray-200 rounded-full h-1.5">
                            <div class="bg-green-600 animate-bar h-1.5 rounded-full" style="width: {{ $totalFoodOrders > 0 ? ($deliveredOrders / $totalFoodOrders) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
