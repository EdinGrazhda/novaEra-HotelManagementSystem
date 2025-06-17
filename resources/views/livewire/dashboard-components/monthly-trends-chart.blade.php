<!-- Monthly Check-in/Check-out Chart Section -->
<div class="bg-white rounded-lg shadow-lg overflow-hidden">
    <div class="p-4 bg-[#f9b903] text-[#1B1B18] flex items-center justify-between">
        <h2 class="text-xl font-semibold">Monthly Check-in/Check-out Trends</h2>
        <button onclick="refreshChartData()" class="text-xs bg-white hover:bg-gray-100 text-[#1B1B18] transition-colors rounded-full px-3 py-1 flex items-center shadow-sm">
            <i class="fas fa-sync-alt mr-1"></i> Refresh
        </button>
    </div>
    <div class="p-6 relative">
        <div class="chart-container">
            <canvas id="monthlyTrendsChart"></canvas>
            <div class="chart-loading" wire:loading wire:target="loadDashboardData">
                <div class="chart-spinner"></div>
            </div>
        </div>
    </div>
</div>

<!-- Initialize the chart -->
<script>
    // Initialize or get monthly trends chart
    let monthlyTrendsChart;
    
    function initMonthlyTrendsChart() {
        const ctx = document.getElementById('monthlyTrendsChart');
        if (!ctx) return null;
        
        // Default sample data for immediate display
        const sampleData = {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            checkins: [15, 22, 18, 25, 30, 28],
            checkouts: [10, 18, 20, 22, 25, 32]
        };
        
        // Chart configuration with CSS variable colors
        const config = {
            type: 'line',
            data: {
                labels: sampleData.labels,
                datasets: [
                    {
                        label: 'Check-ins',
                        data: sampleData.checkins,
                        borderColor: '#3B82F6', // Bright blue for check-ins
                        backgroundColor: 'rgba(59, 130, 246, 0.15)', // Light blue background
                        tension: 0.4,
                        fill: true,
                        borderWidth: 3,
                        pointBackgroundColor: '#3B82F6',
                        pointHoverRadius: 6,
                        pointHoverBorderWidth: 2,
                        pointHoverBackgroundColor: 'white'
                    },
                    {
                        label: 'Check-outs',
                        data: sampleData.checkouts,
                        borderColor: '#f9b903', // Brand gold for check-outs
                        backgroundColor: 'rgba(249, 185, 3, 0.15)', // Light gold background
                        tension: 0.4,
                        fill: true,
                        borderWidth: 3,
                        pointBackgroundColor: '#f9b903',
                        pointHoverRadius: 6,
                        pointHoverBorderWidth: 2,
                        pointHoverBackgroundColor: 'white'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            boxWidth: 12,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            padding: 15
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: 'rgba(255, 255, 255, 0.9)',
                        titleColor: '#1B1B18',
                        bodyColor: '#6b7280',
                        borderColor: 'rgba(249, 185, 3, 0.3)',
                        borderWidth: 1,
                        padding: 10,
                        displayColors: true,
                        caretSize: 8,
                        cornerRadius: 8,
                        titleFont: {
                            weight: '600'
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            drawBorder: false,
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            precision: 0,
                            font: {
                                size: 11
                            },
                            color: 'rgba(0, 0, 0, 0.6)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 11
                            },
                            color: 'rgba(0, 0, 0, 0.6)'
                        }
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                },
                elements: {
                    line: {
                        borderJoinStyle: 'round'
                    }
                }
            }
        };
        
        const chart = new Chart(ctx, config);
        
        // Register chart in the central registry
        if (window.novaEraCharts) {
            window.novaEraCharts.register('MonthlyTrends', chart);
        }
        
        return chart;
    }
    
    function updateMonthlyTrendsChart() {
        console.log('Updating monthly trends chart data');
        
        // Get the chart instance from registry or local variable
        const chart = window.novaEraCharts ? 
            window.novaEraCharts.getChart('MonthlyTrends') : 
            monthlyTrendsChart;
        
        // If chart doesn't exist, initialize it first
        if (!chart) {
            console.log('Chart not found, initializing first');
            monthlyTrendsChart = initMonthlyTrendsChart();
            if (!monthlyTrendsChart) return; // Exit if initialization failed
        }
        
        // Reference to the chart we'll update (either from the registry or local var)
        const chartToUpdate = chart || monthlyTrendsChart;
        
        // Make sure Livewire is initialized
        if (typeof @this === 'undefined') {
            console.error('Livewire component not initialized yet');
            return;
        }
        
        // Show loading state
        const container = document.querySelector('#monthlyTrendsChart').closest('.chart-container');
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
            console.log(`Attempt ${attempts} to load monthly chart data`);
            
            @this.getChartMonthlyData().then(monthlyData => {
                // Hide loading indicator
                if (container && container.querySelector('.chart-loading')) {
                    container.querySelector('.chart-loading').style.display = 'none';
                }
                
                if (monthlyData && monthlyData.length) {
                    console.log('Monthly data loaded successfully:', monthlyData.length, 'items');
                    
                    const labels = monthlyData.map(item => item.month);
                    const checkins = monthlyData.map(item => item.checkins);
                    const checkouts = monthlyData.map(item => item.checkouts);
                    
                    chartToUpdate.data.labels = labels;
                    chartToUpdate.data.datasets[0].data = checkins;
                    chartToUpdate.data.datasets[1].data = checkouts;
                    
                    chartToUpdate.update('normal');
                } else if (attempts < maxAttempts) {
                    console.log(`No data received, retrying in ${500 * attempts}ms...`);
                    setTimeout(attemptDataLoad, 500 * attempts); // Exponential backoff
                } else {
                    console.error('Failed to load monthly data after', maxAttempts, 'attempts');
                }
            }).catch(err => {
                console.error('Error loading monthly data:', err);
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
            monthlyTrendsChart = monthlyTrendsChart || initMonthlyTrendsChart();
            if (monthlyTrendsChart) {
                updateMonthlyTrendsChart();
            }
        }, 100);
    });
    
    // Re-initialize chart when navigating back to dashboard
    document.addEventListener('visibilitychange', () => {
        if (document.visibilityState === 'visible') {
            // Check if canvas exists and has no chart attached
            const ctx = document.getElementById('monthlyTrendsChart');
            if (ctx && !ctx.chart && typeof Chart !== 'undefined') {
                console.log('Reinitializing monthly trends chart after page visibility change');
                setTimeout(() => {
                    // If chart instance exists but is stale, destroy it
                    if (monthlyTrendsChart && monthlyTrendsChart.destroyed) {
                        monthlyTrendsChart = null;
                    }
                    
                    // Reinitialize if needed
                    monthlyTrendsChart = monthlyTrendsChart || initMonthlyTrendsChart();
                    if (monthlyTrendsChart) {
                        updateMonthlyTrendsChart();
                    }
                }, 300);
            }
        }
    });
    
    // Also listen for Alpine JS hook when navigation occurs
    document.addEventListener('alpine:navigated', () => {
        console.log('Navigation detected, ensuring monthly chart is initialized');
        setTimeout(() => {
            const ctx = document.getElementById('monthlyTrendsChart');
            if (ctx && typeof Chart !== 'undefined') {
                // If chart instance exists but is stale, destroy it
                if (monthlyTrendsChart && (monthlyTrendsChart.destroyed || !ctx.chart)) {
                    monthlyTrendsChart = null;
                }
                
                // Reinitialize if needed
                monthlyTrendsChart = monthlyTrendsChart || initMonthlyTrendsChart();
                if (monthlyTrendsChart) {
                    updateMonthlyTrendsChart();
                }
            }
        }, 300);
    });
</script>
