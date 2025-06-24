<!-- Monthly Check-in/Check-out Chart Section -->
<div class="bg-white rounded-lg shadow-lg overflow-hidden">
    <div class="p-4 bg-[#f9b903] text-[#1B1B18] flex items-center justify-between">
        <h2 class="text-xl font-semibold">Monthly Check-in/Check-out Trends</h2>
        <button onclick="refreshMonthlyTrendsChart()" class="text-xs bg-white hover:bg-gray-100 text-[#1B1B18] transition-colors rounded-full px-3 py-1 flex items-center shadow-sm">
            <i class="fas fa-sync-alt mr-1"></i> Refresh
        </button>
    </div>
    <div class="p-6 relative">
        <div class="chart-container">
            <canvas id="monthlyTrendsChart"></canvas>
            <div class="chart-loading" style="display: none;">
                <div class="chart-spinner"></div>
            </div>
        </div>
    </div>
</div>

<!-- Initialize the chart -->
<script>
    // Initialize or get monthly trends chart
    window.monthlyTrendsChart = window.monthlyTrendsChart || null;
    
    // Use function expression instead of declaration to avoid redeclaration issues
    window.initMonthlyTrendsChart = window.initMonthlyTrendsChart || function() {
        const ctx = document.getElementById('monthlyTrendsChart');
        if (!ctx) return null;
        
        // Get chart data from the server-provided JSON
        const monthlyData = {!! isset($monthlyTrends) ? json_encode($monthlyTrends) : '[]' !!};
        
        const labels = monthlyData.map(item => item.month);
        const checkins = monthlyData.map(item => item.checkins);
        const checkouts = monthlyData.map(item => item.checkouts);
        
        // Chart configuration with CSS variable colors
        const config = {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Check-ins',
                        data: checkins,
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
                        data: checkouts,
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
                            padding: 15,
                            boxWidth: 12,
                            font: {
                                size: 12
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
                        cornerRadius: 8
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            drawBorder: false,
                            borderDash: [5, 5]
                        },
                        ticks: {
                            font: {
                                size: 11
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 11
                            }
                        }
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                },
                elements: {
                    point: {
                        radius: 3
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
      // Manual refresh function - use window object to prevent redeclaration
    window.refreshMonthlyTrendsChart = window.refreshMonthlyTrendsChart || function() {
        const container = document.querySelector('#monthlyTrendsChart').closest('.chart-container');
        const loadingDiv = container.querySelector('.chart-loading');
        
        // Show loading spinner
        if (loadingDiv) {
            loadingDiv.style.display = 'flex';
        }
        
        // Fetch fresh data from the server
        fetch('/api/charts/monthly')
            .then(response => response.json())
            .then(data => {
                if (loadingDiv) {
                    loadingDiv.style.display = 'none';
                }
                  // Get the chart instance
                const chartToUpdate = window.novaEraCharts ? 
                    window.novaEraCharts.getChart('MonthlyTrends') : 
                    window.monthlyTrendsChart;
                    
                if (chartToUpdate) {
                    // Update chart with new data
                    const labels = data.map(item => item.month);
                    const checkins = data.map(item => item.checkins);
                    const checkouts = data.map(item => item.checkouts);
                    
                    chartToUpdate.data.labels = labels;
                    chartToUpdate.data.datasets[0].data = checkins;
                    chartToUpdate.data.datasets[1].data = checkouts;
                    
                    chartToUpdate.update('normal');
                } else {
                    console.error('Monthly trends chart not found for update');
                }
            })
            .catch(error => {
                console.error('Error refreshing monthly trend data:', error);
                if (loadingDiv) {
                    loadingDiv.style.display = 'none';
                }
            });
    }
      // Initialize the chart when the DOM is loaded
    if (!window.monthlyTrendsInitialized) {
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                window.monthlyTrendsChart = window.initMonthlyTrendsChart();
                if (window.monthlyTrendsChart) {
                    window.refreshMonthlyTrendsChart();
                }
            }, 100);
        });
        
        // Also handle navigation events from Livewire
        document.addEventListener('livewire:navigated', () => {
            setTimeout(() => {
                const canvas = document.getElementById('monthlyTrendsChart');
                if (canvas) {
                    window.monthlyTrendsChart = window.monthlyTrendsChart || window.initMonthlyTrendsChart();
                    if (window.monthlyTrendsChart) {
                        window.refreshMonthlyTrendsChart();
                    }
                }
            }, 300);
        });
        
        // Mark initialized to prevent duplicate event listeners
        window.monthlyTrendsInitialized = true;
    }
</script>
