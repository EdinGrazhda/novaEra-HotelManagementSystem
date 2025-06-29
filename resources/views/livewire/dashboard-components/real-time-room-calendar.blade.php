<div>
    <!-- Monthly Check-in/Check-out Chart Section -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden" wire:poll.30s="poll">
        <div class="p-4 bg-[#f9b903] text-[#1B1B18] flex justify-between items-center">
            <h2 class="text-xl font-semibold">Monthly Check-in/Check-out Trends</h2>
            <div class="flex items-center gap-2">
                <div class="text-xs mr-2">
                    <span>Updated: {{ $lastUpdated }}</span>
                </div>
                <button wire:click="loadMonthlyData" class="text-xs bg-white hover:bg-gray-100 text-[#1B1B18] transition-colors rounded-full px-3 py-1 flex items-center shadow-sm">
                    <i class="fas fa-sync-alt mr-1"></i> Refresh
                </button>
            </div>
        </div>
        <div class="p-6 relative">
            <div class="chart-container" style="position: relative; height: 300px; width: 100%;">
                <canvas id="monthlyTrendsChart"></canvas>
            </div>
        </div>
    </div>

    <script>
        // Wait for document to be fully loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize chart with hardcoded data to ensure it displays
            initMonthlyChart();
            
            // Try to get real data after a short delay
            setTimeout(updateChartWithLivewireData, 500);
        });
        
        // Chart reference
        let monthlyChart = null;
        
        function initMonthlyChart() {
            // Get the canvas element
            const ctx = document.getElementById('monthlyTrendsChart');
            if (!ctx) {
                console.error("Cannot find monthlyTrendsChart canvas element");
                return;
            }
            
            // Destroy existing chart if it exists
            if (monthlyChart) {
                monthlyChart.destroy();
            }
            
            // Initial data - hardcoded sample data for immediate display
            const sampleData = {
                labels: ['Jan 2025', 'Feb 2025', 'Mar 2025', 'Apr 2025', 'May 2025', 'Jun 2025'],
                checkins: [15, 22, 18, 25, 30, 28],
                checkouts: [10, 18, 20, 22, 25, 32]
            };
            
            // Create Chart.js instance
            monthlyChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: sampleData.labels,
                    datasets: [
                        {
                            label: 'Check-ins',
                            data: sampleData.checkins,
                            borderColor: '#f9b903',
                            backgroundColor: 'rgba(249, 185, 3, 0.2)',
                            tension: 0.4,
                            fill: true,
                            borderWidth: 2
                        },
                        {
                            label: 'Check-outs',
                            data: sampleData.checkouts,
                            borderColor: '#1B1B18',
                            backgroundColor: 'rgba(27, 27, 24, 0.1)',
                            tension: 0.4,
                            fill: true,
                            borderWidth: 2
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
            
            console.log("Monthly trends chart initialized with sample data");
        }
        
        function updateChartWithLivewireData() {
            // Make sure the chart exists
            if (!monthlyChart) {
                initMonthlyChart();
            }
            
            // Get data from Livewire component
            if (typeof @this !== 'undefined') {
                @this.getChartMonthlyData().then(data => {
                    // Check if we have valid data
                    if (!data || !data.length) {
                        console.warn("No data received from Livewire component");
                        return;
                    }
                    
                    console.log("Received data from Livewire:", data);
                    
                    // Extract labels and data
                    const labels = data.map(item => item.month);
                    const checkins = data.map(item => item.checkins);
                    const checkouts = data.map(item => item.checkouts);
                    
                    // Update chart data
                    monthlyChart.data.labels = labels;
                    monthlyChart.data.datasets[0].data = checkins;
                    monthlyChart.data.datasets[1].data = checkouts;
                    
                    // Refresh chart
                    monthlyChart.update();
                    
                    console.log("Chart updated with Livewire data");
                }).catch(error => {
                    console.error("Error getting data from Livewire:", error);
                });
            }
        }
        
        // Listen for Livewire events
        document.addEventListener('livewire:init', () => {
            Livewire.on('monthlyTrendsUpdated', (timestamp) => {
                console.log("Received monthlyTrendsUpdated event:", timestamp);
                updateChartWithLivewireData();
            });
        });
    </script>
</div>
