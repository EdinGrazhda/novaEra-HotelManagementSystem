<div>
    <!-- Real-time Food Status Section -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-8" 
        wire:poll.15s="poll"
        x-data="{
            lastUpdate: '',
            isUpdating: false,
            totalFoodOrders: @entangle('totalFoodOrders'),
            receivedOrders: @entangle('receivedOrders'),
            preparingOrders: @entangle('preparingOrders'),
            deliveredOrders: @entangle('deliveredOrders'),
            fulfillmentRate: @entangle('fulfillmentRate'),
            
            init() {
                this.$wire.on('foodStatusUpdated', (timestamp) => {
                    this.lastUpdate = timestamp;
                    this.highlightChanges();
                    
                    // Update the food orders count in the dashboard header
                    const countElement = document.getElementById('dashboard-food-orders-count');
                    if (countElement) {
                        countElement.innerText = this.$wire.totalFoodOrders;
                        countElement.classList.add('highlight-change');
                        setTimeout(() => {
                            countElement.classList.remove('highlight-change');
                        }, 2000);
                    }
                });
            },
            
            highlightChanges() {
                this.isUpdating = true;
                setTimeout(() => {
                    this.isUpdating = false;
                }, 2000);
            },
            
            getBarWidth(value) {
                return this.$wire.totalFoodOrders > 0 ? (value / this.$wire.totalFoodOrders) * 100 : 0;
            }
        }">
        <div class="p-4 bg-[#f9b903] text-[#1B1B18] flex justify-between items-center">
            <h2 class="text-xl font-semibold">Restaurant & Food Services</h2>
            <div class="text-xs flex items-center gap-1">
                <span>Updated: {{ $lastUpdated }}</span>
                <span x-show="isUpdating" class="inline-block ml-1">
                    <i class="fas fa-sync-alt fa-spin text-[#1B1B18]"></i>
                </span>
            </div>
        </div>
        <div class="p-6">
            <!-- Orders Overview -->
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-8">
                <div class="flex items-center mb-4 md:mb-0">
                    <div class="rounded-full bg-[#f9b903] p-3 mr-3"
                        x-bind:class="{'pulse-animation': isUpdating && $wire.totalFoodOrders != totalFoodOrders}">
                        <i class="fas fa-utensils text-white text-lg"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Orders Today</p>
                        <p class="text-3xl font-bold text-gray-800 transition-all duration-300"
                            x-text="$wire.totalFoodOrders"
                            x-bind:class="{'highlight-change': isUpdating && $wire.totalFoodOrders != totalFoodOrders}">{{ $totalFoodOrders ?? 0 }}</p>
                    </div>
                </div>
                
                <!-- Order Fulfillment Rate -->
                <div class="bg-gray-50 rounded-lg p-3">
                    <div class="flex items-center">
                        <div class="text-right mr-3">
                            <p class="text-sm text-gray-500">Order Fulfillment</p>
                            <p class="font-bold text-lg text-gray-800 transition-all duration-300"
                                x-text="$wire.fulfillmentRate + '%'"
                                x-bind:class="{'highlight-change': isUpdating && $wire.fulfillmentRate != fulfillmentRate}">{{ $fulfillmentRate }}%</p>
                        </div>
                        <div class="rounded-full p-2 transition-colors duration-300"
                             x-bind:class="{ 
                                'bg-green-500': $wire.fulfillmentRate > 75, 
                                'bg-amber-500': $wire.fulfillmentRate <= 75
                             }">
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
                        <div class="absolute flex items-center justify-center w-10 h-10 rounded-full border-4 border-white bg-blue-100 shadow-sm"
                             x-bind:class="{'highlight-change': isUpdating && $wire.receivedOrders != receivedOrders}">
                            <i class="fas fa-clipboard-list text-blue-600"></i>
                        </div>
                        <div class="pl-16">
                            <h4 class="font-semibold text-gray-700">Received</h4>
                            <div class="flex items-end">
                                <span class="text-3xl font-bold text-blue-600 mr-2 transition-all duration-300"
                                    x-text="$wire.receivedOrders"
                                    x-bind:class="{'highlight-change': isUpdating && $wire.receivedOrders != receivedOrders}">{{ $receivedOrders ?? 0 }}</span>
                                <span class="text-gray-500 text-sm">orders</span>
                            </div>
                            <div class="mt-2 w-full bg-gray-200 rounded-full h-1.5 overflow-hidden">
                                <div class="bg-blue-600 h-1.5 rounded-full transition-all duration-500"
                                    x-bind:style="{ width: getBarWidth($wire.receivedOrders) + '%' }"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Preparing Orders -->
                    <div class="relative flex items-center mb-8">
                        <div class="absolute flex items-center justify-center w-10 h-10 rounded-full border-4 border-white bg-amber-100 shadow-sm"
                             x-bind:class="{'highlight-change': isUpdating && $wire.preparingOrders != preparingOrders}">
                            <i class="fas fa-fire text-amber-600"></i>
                        </div>
                        <div class="pl-16">
                            <h4 class="font-semibold text-gray-700">Preparing</h4>
                            <div class="flex items-end">
                                <span class="text-3xl font-bold text-amber-600 mr-2 transition-all duration-300"
                                    x-text="$wire.preparingOrders"
                                    x-bind:class="{'highlight-change': isUpdating && $wire.preparingOrders != preparingOrders}">{{ $preparingOrders ?? 0 }}</span>
                                <span class="text-gray-500 text-sm">orders</span>
                            </div>
                            <div class="mt-2 w-full bg-gray-200 rounded-full h-1.5 overflow-hidden">
                                <div class="bg-amber-500 h-1.5 rounded-full transition-all duration-500"
                                    x-bind:style="{ width: getBarWidth($wire.preparingOrders) + '%' }"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Delivered Orders -->
                    <div class="relative flex items-center">
                        <div class="absolute flex items-center justify-center w-10 h-10 rounded-full border-4 border-white bg-green-100 shadow-sm"
                             x-bind:class="{'highlight-change': isUpdating && $wire.deliveredOrders != deliveredOrders}">
                            <i class="fas fa-check-circle text-green-600"></i>
                        </div>
                        <div class="pl-16">
                            <h4 class="font-semibold text-gray-700">Delivered</h4>
                            <div class="flex items-end">
                                <span class="text-3xl font-bold text-green-600 mr-2 transition-all duration-300"
                                    x-text="$wire.deliveredOrders"
                                    x-bind:class="{'highlight-change': isUpdating && $wire.deliveredOrders != deliveredOrders}">{{ $deliveredOrders ?? 0 }}</span>
                                <span class="text-gray-500 text-sm">orders</span>
                            </div>
                            <div class="mt-2 w-full bg-gray-200 rounded-full h-1.5 overflow-hidden">
                                <div class="bg-green-600 h-1.5 rounded-full transition-all duration-500"
                                    x-bind:style="{ width: getBarWidth($wire.deliveredOrders) + '%' }"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
    @keyframes pulse-animation {
        0% { transform: scale(1); }
        50% { transform: scale(1.1); box-shadow: 0 0 15px rgba(249, 185, 3, 0.6); }
        100% { transform: scale(1); }
    }

    .pulse-animation {
        animation: pulse-animation 1s ease-in-out;
    }
    </style>
</div>
