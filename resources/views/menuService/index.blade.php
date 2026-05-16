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
        .dark .menu-service-card {
            border-color: #374151;
            background-color: #1f2937;
        }
        .menu-service-card:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .dark .menu-service-card:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.4);
        }
        .status-received {
            background-color: #dbeafe;
            color: #1e40af;
        }
        .dark .status-received {
            background-color: rgba(29, 78, 216, 0.2);
            color: #93c5fd;
        }
        .status-in_process {
            background-color: #fef9c3;
            color: #854d0e;
        }
        .dark .status-in_process {
            background-color: rgba(161, 98, 7, 0.2);
            color: #fcd34d;
        }
        .status-delivered {
            background-color: #dcfce7;
            color: #166534;
        }
        .dark .status-delivered {
            background-color: rgba(22, 101, 52, 0.2);
            color: #86efac;
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
        <!-- Current Orders -->
        <livewire:real-time-menu-service :room-filter="request('room_filter')" :status-filter="request('status_filter')" :search="request('search')" />
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle filter form submission
            const form = document.querySelector('.menu-filter-form');
            if (form) {
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
                });
            }            // Handle menu item checkboxes to show/hide quantity controls
            const checkboxes = document.querySelectorAll('.menu-checkbox');
            if (checkboxes.length > 0) {
                checkboxes.forEach(checkbox => {
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
            }

            // Form validation before submission
            const orderForm = document.getElementById('orderForm');
            if (orderForm) {
                orderForm.addEventListener('submit', function(e) {
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
            }
        });
    </script>
</x-layouts.app>