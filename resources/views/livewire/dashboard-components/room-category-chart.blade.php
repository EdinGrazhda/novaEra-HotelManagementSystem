<!-- Room Category Distribution Chart Section -->
<div class="bg-white rounded-lg shadow-lg overflow-hidden">
    <div class="p-4 bg-[#f9b903] text-[#1B1B18] flex items-center justify-between">
        <h2 class="text-xl font-semibold">Room Category Distribution</h2>
        <button onclick="refreshChartData()" class="text-xs bg-white hover:bg-gray-100 text-[#1B1B18] transition-colors rounded-full px-3 py-1 flex items-center shadow-sm">
            <i class="fas fa-sync-alt mr-1"></i> Refresh
        </button>
    </div>
    <div class="p-6 relative">
        <div class="chart-container">
            <canvas id="roomCategoryChart"></canvas>
            <div class="chart-loading" wire:loading wire:target="loadDashboardData">
                <div class="chart-spinner"></div>
            </div>
        </div>
    </div>
</div>

<!-- Initialize the chart -->
<script>
    // Initialize or get room category chart
    let roomCategoryChart;
    
    function initRoomCategoryChart() {
        const ctx = document.getElementById('roomCategoryChart');
        if (!ctx) return null;
        
        // Sample data to show immediately
        const sampleData = {
            labels: ['Standard', 'Deluxe', 'Suite', 'Presidential'],
            values: [12, 8, 5, 2]
        };
        
        // Define brand-matching colors with CSS variables where possible
        const backgroundColors = [
            'rgba(249, 185, 3, 0.9)',     // Main brand color
            'rgba(249, 185, 3, 0.7)',     // Lighter version
            'rgba(249, 185, 3, 0.5)',     // Even lighter
            'rgba(27, 27, 24, 0.8)',      // Dark brand color
            'rgba(27, 27, 24, 0.6)',      // Lighter dark
            'rgba(245, 158, 11, 0.7)',    // Amber
            'rgba(217, 119, 6, 0.7)',     // Amber dark
            'rgba(120, 113, 108, 0.7)',   // Neutral
            'rgba(168, 162, 158, 0.7)',   // Light neutral
            'rgba(214, 211, 209, 0.7)'    // Lightest neutral
        ];
        
        // Chart configuration
        const config = {
            type: 'doughnut',
            data: {
                labels: sampleData.labels,
                datasets: [{
                    data: sampleData.values,
                    backgroundColor: backgroundColors,
                    borderColor: backgroundColors.map(color => color.replace(/[0-9]\.([0-9])/, '1')),
                    borderWidth: 1,
                    hoverOffset: 8,
                    borderRadius: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            boxWidth: 12,
                            padding: 15,
                            font: {
                                size: 11
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(255, 255, 255, 0.9)',
                        titleColor: '#1B1B18',
                        bodyColor: '#6b7280',
                        borderColor: 'rgba(249, 185, 3, 0.3)',
                        borderWidth: 1,
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                },
                cutout: '65%',
                animation: {
                    animateRotate: true,
                    animateScale: true,
                    duration: 800,
                    easing: 'easeOutQuart'
                },
                layout: {
                    padding: {
                        top: 10,
                        bottom: 10,
                        left: 10,
                        right: 10
                    }
                }
            }
        };
        
        // Create chart
        const chart = new Chart(ctx, config);
        
        // Register chart in the central registry
        if (window.novaEraCharts) {
            window.novaEraCharts.register('RoomCategory', chart);
        }
        
        return chart;
    }
    
    function updateRoomCategoryChart() {
        console.log('Updating room category chart data');
        
        // Get the chart instance from registry or local variable
        const chart = window.novaEraCharts ? 
            window.novaEraCharts.getChart('RoomCategory') : 
            roomCategoryChart;
        
        // If chart doesn't exist, initialize it first
        if (!chart) {
            console.log('Room category chart not found, initializing first');
            roomCategoryChart = initRoomCategoryChart();
            if (!roomCategoryChart) return; // Exit if initialization failed
        }
        
        // Reference to the chart we'll update
        const chartToUpdate = chart || roomCategoryChart;
        
        // Make sure Livewire is initialized
        if (typeof @this === 'undefined') {
            console.error('Livewire component not initialized yet');
            return;
        }
        
        // Show loading state
        const container = document.querySelector('#roomCategoryChart').closest('.chart-container');
        if (container) {
            const loadingDiv = container.querySelector('.chart-loading');
            if (loadingDiv) {
                loadingDiv.style.display = 'flex';
            }
        }
        
        // Try to get data with exponential backoff
        let attempts = 0;
        const maxAttempts = 5;
        
        function attemptDataLoad() {
            attempts++;
            console.log(`Attempt ${attempts} to load room category chart data`);
            
            @this.getChartRoomCategories().then(categoryData => {
                // Hide loading indicator
                if (container && container.querySelector('.chart-loading')) {
                    container.querySelector('.chart-loading').style.display = 'none';
                }
                
                if (categoryData && categoryData.length) {
                    console.log('Room category data loaded successfully:', categoryData.length, 'items');
                    
                    const labels = categoryData.map(item => item.category);
                    const values = categoryData.map(item => item.count);
                    
                    chartToUpdate.data.labels = labels;
                    chartToUpdate.data.datasets[0].data = values;
                    
                    chartToUpdate.update('normal');
                } else if (attempts < maxAttempts) {
                    console.log(`No category data received, retrying in ${500 * attempts}ms...`);
                    setTimeout(attemptDataLoad, 500 * attempts); // Exponential backoff
                } else {
                    console.error('Failed to load room category data after', maxAttempts, 'attempts');
                }
            }).catch(err => {
                console.error('Error loading room category data:', err);
                if (attempts < maxAttempts) {
                    setTimeout(attemptDataLoad, 500 * attempts);
                }
            });
        }
        
        // Start the first attempt
        attemptDataLoad();
    }
    
    // Initialize the chart when Livewire is ready
    document.addEventListener('livewire:initialized', () => {
        // Use a timeout to ensure DOM is fully rendered
        setTimeout(() => {
            roomCategoryChart = roomCategoryChart || initRoomCategoryChart();
            if (roomCategoryChart) {
                updateRoomCategoryChart();
            }
        }, 100);
    });
    
    // Re-initialize chart when navigating back to dashboard
    document.addEventListener('visibilitychange', () => {
        if (document.visibilityState === 'visible') {
            // Check if canvas exists and has no chart attached
            const ctx = document.getElementById('roomCategoryChart');
            if (ctx && !ctx.chart && typeof Chart !== 'undefined') {
                console.log('Reinitializing room category chart after page visibility change');
                setTimeout(() => {
                    // If chart instance exists but is stale, destroy it
                    if (roomCategoryChart && roomCategoryChart.destroyed) {
                        roomCategoryChart = null;
                    }
                    
                    // Reinitialize if needed
                    roomCategoryChart = roomCategoryChart || initRoomCategoryChart();
                    if (roomCategoryChart) {
                        updateRoomCategoryChart();
                    }
                }, 300);
            }
        }
    });
    
    // Also listen for Alpine JS hook when navigation occurs
    document.addEventListener('alpine:navigated', () => {
        console.log('Navigation detected, ensuring room category chart is initialized');
        setTimeout(() => {
            const ctx = document.getElementById('roomCategoryChart');
            if (ctx && typeof Chart !== 'undefined') {
                // If chart instance exists but is stale, destroy it
                if (roomCategoryChart && (roomCategoryChart.destroyed || !ctx.chart)) {
                    roomCategoryChart = null;
                }
                
                // Reinitialize if needed
                roomCategoryChart = roomCategoryChart || initRoomCategoryChart();
                if (roomCategoryChart) {
                    updateRoomCategoryChart();
                }
            }
        }, 300);
    });
</script>
