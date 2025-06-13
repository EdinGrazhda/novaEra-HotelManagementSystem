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
                labels: [],
                datasets: [{
                    data: [],
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
        // Get the chart instance from registry or local variable
        const chart = window.novaEraCharts ? 
            window.novaEraCharts.getChart('RoomCategory') : 
            roomCategoryChart;
        
        // If chart doesn't exist, stop
        if (!chart) return;
        
        @this.getChartRoomCategories().then(categoryData => {
            if (!categoryData || !categoryData.length) return;
            
            const labels = categoryData.map(item => item.category);
            const values = categoryData.map(item => item.count);
            
            chart.data.labels = labels;
            chart.data.datasets[0].data = values;
            
            chart.update('normal');
        });
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
</script>
