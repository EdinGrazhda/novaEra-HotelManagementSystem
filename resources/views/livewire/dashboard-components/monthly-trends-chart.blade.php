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
        
        // Chart configuration with CSS variable colors
        const config = {
            type: 'line',
            data: {
                labels: [],
                datasets: [
                    {
                        label: 'Check-ins',
                        data: [],
                        borderColor: 'var(--brand-gold)',
                        backgroundColor: 'rgba(249, 185, 3, 0.2)',
                        tension: 0.4,
                        fill: true,
                        borderWidth: 2,
                        pointBackgroundColor: 'var(--brand-gold)',
                        pointHoverRadius: 6,
                        pointHoverBorderWidth: 2,
                        pointHoverBackgroundColor: 'white'
                    },
                    {
                        label: 'Check-outs',
                        data: [],
                        borderColor: 'var(--brand-dark)',
                        backgroundColor: 'rgba(27, 27, 24, 0.1)',
                        tension: 0.4,
                        fill: true,
                        borderWidth: 2,
                        pointBackgroundColor: 'var(--status-danger)',
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
        // Get the chart instance from registry or local variable
        const chart = window.novaEraCharts ? 
            window.novaEraCharts.getChart('MonthlyTrends') : 
            monthlyTrendsChart;
        
        // If chart doesn't exist, stop
        if (!chart) return;
        
        @this.getChartMonthlyData().then(monthlyData => {
            if (!monthlyData || !monthlyData.length) return;
            
            const labels = monthlyData.map(item => item.month);
            const checkins = monthlyData.map(item => item.checkins);
            const checkouts = monthlyData.map(item => item.checkouts);
            
            chart.data.labels = labels;
            chart.data.datasets[0].data = checkins;
            chart.data.datasets[1].data = checkouts;
            
            chart.update('normal');
        });
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
</script>
