<x-layouts.app :title="__('Menu Service')">
    <!-- Include custom CSS -->
    <link href="{{ asset('css/room-overrides.css') }}" rel="stylesheet">
    <link href="{{ asset('css/room-card-fixes.css') }}" rel="stylesheet">
    <link href="{{ asset('css/room-status-colors.css') }}" rel="stylesheet">
    <style>
        /* Menu Service specific styles */
        .menu-service-card {
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }
        .menu-service-card:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .status-received {
            background-color: #dbeafe;
            color: #1e40af;
        }
        .status-in_process {
            background-color: #fef9c3;
            color: #854d0e;
        }
        .status-delivered {
            background-color: #dcfce7;
            color: #166534;
        }
        .quantity-control {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        .quantity-btn {
            border-radius: 9999px;
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            cursor: pointer;
        }
        .menu-item-selector {
            max-height: 200px;
            overflow-y: auto;
        }
    </style>
    <!-- Add CSRF Token meta tag -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="container mx-auto px-4 py-8">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-[#1B1B18]">Menu Service</h1>
            <p class="text-gray-600">Assign menu items to rooms and manage food orders</p>
        </div>

        <!-- Filter and search bar -->
        <div class="bg-white p-4 rounded-lg shadow-sm mb-6">
            <form action="{{ route('menuService.index') }}" method="GET" class="menu-filter-form">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Room Filter -->
                    <div>
                        <label for="room_filter" class="block text-sm font-medium text-gray-700 mb-1">Filter by Room</label>
                        <select name="room_filter" id="room_filter" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-[#F8B803] focus:border-[#F8B803]">
                            <option value="">All Rooms</option>
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}" {{ request('room_filter') == $room->id ? 'selected' : '' }}>
                                    Room {{ $room->room_number }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label for="status_filter" class="block text-sm font-medium text-gray-700 mb-1">Filter by Status</label>
                        <div class="flex gap-2 flex-wrap">
                            <button type="button" name="status_filter" value="" class="px-3 py-1 rounded-md text-sm {{ !request('status_filter') ? 'bg-[#F8B803] text-white' : 'bg-gray-200 text-gray-800 hover:bg-gray-300' }}">
                                All
                            </button>
                            <button type="button" name="status_filter" value="received" class="px-3 py-1 rounded-md text-sm {{ request('status_filter') == 'received' ? 'bg-blue-200 text-blue-800' : 'bg-gray-200 text-gray-800 hover:bg-gray-300' }}">
                                Received
                            </button>
                            <button type="button" name="status_filter" value="in_process" class="px-3 py-1 rounded-md text-sm {{ request('status_filter') == 'in_process' ? 'bg-yellow-200 text-yellow-800' : 'bg-gray-200 text-gray-800 hover:bg-gray-300' }}">
                                In Process
                            </button>
                            <button type="button" name="status_filter" value="delivered" class="px-3 py-1 rounded-md text-sm {{ request('status_filter') == 'delivered' ? 'bg-green-200 text-green-800' : 'bg-gray-200 text-gray-800 hover:bg-gray-300' }}">
                                Delivered
                            </button>
                        </div>
                    </div>

                    <!-- Search -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                        <div class="relative">
                            <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search by room number or menu item..."
                                class="w-full pl-10 pr-4 py-2 border-gray-300 rounded-md shadow-sm focus:ring focus:ring-[#F8B803] focus:border-[#F8B803]">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <button type="submit" class="absolute inset-y-0 right-0 px-3 bg-[#F8B803] text-white rounded-r-md hover:bg-yellow-600 focus:outline-none">
                                Search
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- New Order Form -->
        @can('create-menu-order')
        <div class="bg-white p-4 rounded-lg shadow-sm mb-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Create New Food Order</h2>
            <form action="{{ route('menuService.store') }}" method="POST" id="orderForm">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <!-- Room Selection -->
                    <div>
                        <label for="room_id" class="block text-sm font-medium text-gray-700 mb-1">Select Room</label>
                        <select name="room_id" id="room_id" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-[#F8B803] focus:border-[#F8B803]">
                            <option value="">-- Select a Room --</option>
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}">Room {{ $room->room_number }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Notes -->
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Special Instructions</label>
                        <textarea name="notes" id="notes" rows="1" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-[#F8B803] focus:border-[#F8B803]" placeholder="Any special instructions..."></textarea>
                    </div>
                </div>                <!-- Menu Items Selection -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Menu Items</label>
                    <div class="border border-gray-300 rounded-md p-3 menu-item-selector">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3" id="menuItemsContainer">
                            @foreach($menu_items as $item)
                            <div class="flex items-center space-x-3 p-3 border border-gray-200 rounded-md menu-item">
                                <input type="checkbox" name="selected_items[]" value="{{ $item->id }}" id="menu_{{ $item->id }}" class="menu-checkbox h-5 w-5 text-[#F8B803]" data-id="{{ $item->id }}">
                                <input type="hidden" name="menu_items[{{ $item->id }}][menu_id]" value="{{ $item->id }}" disabled class="menu-id-input">
                                <label for="menu_{{ $item->id }}" class="flex-grow">
                                    <span class="font-medium">{{ $item->name }}</span>
                                    <p class="text-sm text-gray-500">{{ $item->description }}</p>
                                </label>
                                <div class="quantity-control hidden">
                                    <button type="button" class="quantity-btn decrease bg-red-100 text-red-600 hover:bg-red-200" data-id="{{ $item->id }}">-</button>
                                    <input type="number" name="menu_items[{{ $item->id }}][quantity]" value="1" min="1" max="20" class="w-12 text-center border-gray-300 rounded-md quantity-input" disabled>
                                    <button type="button" class="quantity-btn increase bg-green-100 text-green-600 hover:bg-green-200" data-id="{{ $item->id }}">+</button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-[#F8B803] text-[#1B1B18] font-medium rounded-md hover:bg-yellow-500 transition duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Create Order
                    </button>
                </div>
            </form>
        </div>
        @endcan

        <!-- Current Orders -->
        <div class="mb-4">
            <h2 class="text-xl font-semibold mb-4">Current Orders</h2>
            
            @if($orders->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($orders as $order)
                        <div class="menu-service-card p-4 {{ 'status-' . $order->status }}">
                            <div class="flex justify-between items-start mb-3">
                                <h3 class="font-bold">Room {{ $order->room->room_number }}</h3>
                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $order->status_color }}">
                                    {{ $order->status_label }}
                                </span>
                            </div>
                            <div class="mb-3">
                                <p class="font-medium">{{ $order->menu->name }}</p>
                                <p class="text-sm text-gray-600">{{ $order->menu->description }}</p>
                                <p class="mt-1">
                                    <span class="font-medium">Quantity:</span> {{ $order->quantity }}
                                </p>
                                @if($order->notes)
                                    <p class="mt-2 text-sm italic">
                                        <span class="font-medium">Notes:</span> {{ $order->notes }}
                                    </p>
                                @endif
                            </div>
                            <div class="flex justify-between items-center text-xs text-gray-500">
                                <span>Ordered: {{ $order->created_at->format('M d, g:i A') }}</span>
                                
                                <div class="flex gap-1">
                                    <form action="{{ route('menuService.updateStatus', $order) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        @if($order->status === 'received')
                                            <input type="hidden" name="status" value="in_process">
                                            <button type="submit" class="px-2 py-1 bg-yellow-500 text-white text-xs rounded hover:bg-yellow-600">
                                                Start Preparing
                                            </button>
                                        @elseif($order->status === 'in_process')
                                            <input type="hidden" name="status" value="delivered">
                                            <button type="submit" class="px-2 py-1 bg-green-500 text-white text-xs rounded hover:bg-green-600">
                                                Mark Delivered
                                            </button>
                                        @endif
                                    </form>
                                    
                                    @can('create-menu-order')
                                    <form action="{{ route('menuService.destroy', $order) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Are you sure you want to cancel this order?')" class="px-2 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600">
                                            Cancel
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 bg-white rounded-lg shadow">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900">No orders found</h3>
                    <p class="text-gray-500 mt-2">Start by creating a new food order above.</p>
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle filter form submission
            const form = document.querySelector('.menu-filter-form');
            const searchInput = form.querySelector('input[name="search"]');
            
            // Handle filter button clicks
            document.querySelectorAll('button[name="status_filter"]').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Get the value
                    const value = this.getAttribute('value');
                    
                    // Create or update form parameters
                    const formData = new FormData(form);
                    formData.set('status_filter', value);
                    
                    // Build the URL with all parameters
                    let url = form.action + '?';
                    for (const [key, val] of formData.entries()) {
                        if (val) {
                            url += encodeURIComponent(key) + '=' + encodeURIComponent(val) + '&';
                        }
                    }
                    
                    // Navigate to the filtered URL
                    window.location.href = url.slice(0, -1);  // Remove trailing &
                });
            });            // Handle menu item checkboxes to show/hide quantity controls
            document.querySelectorAll('.menu-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const menuItem = this.closest('.menu-item');
                    const quantityControl = menuItem.querySelector('.quantity-control');
                    const menuIdInput = menuItem.querySelector('.menu-id-input');
                    const quantityInput = menuItem.querySelector('.quantity-input');
                    
                    if (this.checked) {
                        quantityControl.classList.remove('hidden');
                        menuIdInput.disabled = false;
                        quantityInput.disabled = false;
                    } else {
                        quantityControl.classList.add('hidden');
                        menuIdInput.disabled = true;
                        quantityInput.disabled = true;
                    }
                });
            });

            // Handle quantity increase/decrease buttons
            document.querySelectorAll('.quantity-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const itemId = this.dataset.id;
                    const input = this.parentNode.querySelector('.quantity-input');
                    let value = parseInt(input.value);
                    
                    if (this.classList.contains('increase')) {
                        if (value < parseInt(input.getAttribute('max'))) {
                            input.value = value + 1;
                        }
                    } else {
                        if (value > parseInt(input.getAttribute('min'))) {
                            input.value = value - 1;
                        }
                    }
                });
            });            // Form validation before submission
            document.getElementById('orderForm').addEventListener('submit', function(e) {
                const checkedItems = document.querySelectorAll('.menu-checkbox:checked');
                if (checkedItems.length === 0) {
                    e.preventDefault();
                    alert('Please select at least one menu item.');
                    return;
                }
                
                // Additional validation to ensure the form data structure is correct
                checkedItems.forEach(item => {
                    const itemId = item.dataset.id;
                    const menuIdInput = document.querySelector(`input[name="menu_items[${itemId}][menu_id]"]`);
                    const quantityInput = document.querySelector(`input[name="menu_items[${itemId}][quantity]"]`);
                    
                    // Make sure the inputs are enabled for selected items
                    if (menuIdInput && quantityInput) {
                        menuIdInput.disabled = false;
                        quantityInput.disabled = false;
                    }
                });
            });
        });
    </script>
</x-layouts.app>