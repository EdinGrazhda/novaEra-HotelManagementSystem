<style>
    /* Color variables for consistent brand colors across all components */
    :root {
        /* Primary brand colors */
        --brand-gold: #f9b903;
        --brand-gold-light: rgba(249, 185, 3, 0.15);
        --brand-gold-lighter: rgba(249, 185, 3, 0.05);
        --brand-gold-dark: #e6aa00;
        
        /* Dark colors */
        --brand-dark: #1B1B18;
        --brand-dark-80: rgba(27, 27, 24, 0.8);
        --brand-dark-60: rgba(27, 27, 24, 0.6);
        --brand-dark-40: rgba(27, 27, 24, 0.4);
        
        /* Neutral colors */
        --neutral-50: #f9fafb;
        --neutral-100: #f3f4f6;
        --neutral-200: #e5e7eb;
        --neutral-300: #d1d5db;
        --neutral-400: #9ca3af;
        --neutral-500: #6b7280;
        --neutral-600: #4b5563;
        --neutral-700: #374151;
        --neutral-800: #1f2937;
        --neutral-900: #111827;
        
        /* Status colors */
        --status-        <!-- Initialize the monthly chart -->
        if (document.getElementById('monthlyActivityChart')) {
            // Show loading indicator
            const loadingElement = document.getElementById('chart-loading');
            if (loadingElement) loadingElement.classList.remove('hidden');
            
            // Initialize and load data
            setTimeout(() => {
                monthlyChart = initMonthlyChart();
                refreshMonthlyChart();
            }, 300);
        }ss: #10B981;
        --status-info: #3B82F6;
        --status-warning: #F59E0B;
        --status-purple: #8B5CF6;
        --status-danger: #EF4444;
    }


    
    .dashboard-stat-card {
        transition: all 0.3s ease;
    }
    
    .dashboard-stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.08);
    }
    
    /* Responsive adjustments */
    @media (max-width: 640px) {
        .chart-container {
            height: 250px;
        }
        
        .dashboard-stat-card {
            margin-bottom: 1rem;
        }
        
        .mobile-stack {
            flex-direction: column;
        }
        
        .mobile-full-width {
            width: 100%;
        }
        
        .mobile-text-center {
            text-align: center;
        }
        
        .mobile-hide {
            display: none;
        }
    }
    
    /* Tablet adjustments */
    @media (min-width: 641px) and (max-width: 1024px) {
        .tablet-stack {
            flex-direction: column;
        }
        
        .tablet-grid-half {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    /* Touch device optimizations */
    @media (hover: none) {
        .hover-only {
            display: none;
        }
    }
    
    /* Room status real-time highlight effect */
    .highlight-change {
        animation: pulse-highlight 2s ease-in-out;
    }
    
    @keyframes pulse-highlight {
        0% { background-color: transparent; }
        25% { background-color: rgba(249, 185, 3, 0.3); }
        75% { background-color: rgba(249, 185, 3, 0.3); }
        100% { background-color: transparent; }
    }
        
        .touch-target {
            min-height: 44px;
            min-width: 44px;
        }
    }
    
    /* Nice animations for dashboard cards */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translate3d(0, 20px, 0);
        }
        to {
            opacity: 1;
            transform: translate3d(0, 0, 0);
        }
    }
    
    .fade-in-up {
        animation: fadeInUp 0.5s ease-out;
    }
    
    /* Staggered animation for multiple elements */
    .fade-in-up:nth-child(1) { animation-delay: 0.1s; }
    .fade-in-up:nth-child(2) { animation-delay: 0.2s; }
    .fade-in-up:nth-child(3) { animation-delay: 0.3s; }
    .fade-in-up:nth-child(4) { animation-delay: 0.4s; }
    
    /* Progress bar animation */
    @keyframes growWidth {
        from { width: 0; }
    }
    
    .animate-bar {
        animation: growWidth 1s ease-out forwards;
    }
    
    /* Chart container styling */
    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }
    
    /* Active touch styling for mobile */
    .chart-container.active-touch {
        cursor: grabbing;
    }
    
    /* Additional brand styling */
    .brand-shadow {
        box-shadow: 0 4px 12px rgba(249, 185, 3, 0.15);
    }
    
    .brand-border {
        border: 1px solid rgba(249, 185, 3, 0.3);
    }
    
    /* Status colors with consistent styling using CSS variables */
    .status-available { color: var(--status-success); }
    .status-occupied { color: var(--status-info); }
    .status-maintenance { color: var(--status-warning); }
    .status-cleaning { color: var(--status-purple); }
    .status-alert { color: var(--status-danger); }
    
    /* Status background colors */
    .status-bg-available { background-color: var(--status-success); }
    .status-bg-occupied { background-color: var(--status-info); }
    .status-bg-maintenance { background-color: var(--status-warning); }
    .status-bg-cleaning { background-color: var(--status-purple); }
    .status-bg-alert { background-color: var(--status-danger); }
    
    /* Status light background colors */
    
    /* Check-in/Check-out specific animations */
    @keyframes pulse-number {
        0% { transform: scale(1); }
        50% { transform: scale(1.15); color: var(--brand-gold); }
        100% { transform: scale(1); }
    }
    
    .pulse-number {
        animation: pulse-number 1s ease-in-out;
    }
    .status-bg-available-light { background-color: rgba(16, 185, 129, 0.1); }
    .status-bg-occupied-light { background-color: rgba(59, 130, 246, 0.1); }
    .status-bg-maintenance-light { background-color: rgba(245, 158, 11, 0.1); }
    .status-bg-cleaning-light { background-color: rgba(139, 92, 246, 0.1); }
    .status-bg-alert-light { background-color: rgba(239, 68, 68, 0.1); }
    
    /* Pulse animation for indicators */
    @keyframes pulse-animation {
        0% {
            box-shadow: 0 0 0 0 rgba(249, 185, 3, 0.7);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(249, 185, 3, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(249, 185, 3, 0);
        }
    }
    
    /* Animation for green status dot */
    @keyframes pulse-green {
        0% {
            transform: scale(0.95);
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
        }
        
        70% {
            transform: scale(1);
            box-shadow: 0 0 0 6px rgba(16, 185, 129, 0);
        }
        
        100% {
            transform: scale(0.95);
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
        }
    }
    
    /* Generic elements with pulse animation */
    .pulse-animation {
        animation: pulse-animation 2s infinite;
    }
    
    /* Green dot specific pulse animation */
    .animate-pulse-green {
        animation: pulse-green 2s infinite;
    }
    
    /* Custom animation for dashboard elements */
    @keyframes float {
        0% {
            transform: translateY(0px);
        }
        50% {
            transform: translateY(-5px);
        }
        100% {
            transform: translateY(0px);
        }
    }
    
    .animate-float {
        animation: float 4s ease-in-out infinite;
    }
</style>

<!-- Dashboard JS -->

<div 
    
    wire:init="loadDashboardData"
    wire:loading.class.delay="opacity-75"
    id="dashboard-main-container"
    class="container mx-auto px-4 py-8"
>
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-8">
        <div class="bg-[#f9b903] p-6">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between">
                <div class="mb-4 md:mb-0">
                    <h1 class="text-2xl font-semibold text-[#1B1B18]">Hotel Management Dashboard</h1>
                    <p class="text-[#1B1B18] opacity-80">Welcome to the NovaERA Hotel Management System</p>
                </div>
                <div class="flex items-center space-x-2">
                    <!-- Loading indicator -->
                    <div class="text-sm text-[#1B1B18] bg-white bg-opacity-50 py-1 px-3 rounded-full flex items-center animate-pulse" wire:loading>
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-[#1B1B18]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Loading data...</span>
                    </div>
                    
                    <!-- Last updated indicator -->
                    <div class="text-sm bg-white bg-opacity-80 py-1 px-3 rounded-full flex items-center shadow-sm transition-all duration-500 ease-in-out" id="update-indicator">
                        <i class="fas fa-clock mr-2 text-[#f9b903]"></i>
                        <span>Last updated: <span id="last-update-time">{{ $lastUpdated ?? now()->format('H:i:s') }}</span></span>
                        <div class="ml-2 h-2 w-2 rounded-full bg-green-500 animate-pulse-green" title="Auto-refresh active every 15 seconds"></div>
                    </div>
                    
                    <!-- Manual refresh button -->
                    <button 
                        wire:click="refreshDashboard" 
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-70"
                        class="text-sm text-[#1B1B18] bg-white py-1 px-4 rounded-full flex items-center hover:bg-opacity-90 transition-colors shadow-sm"
                    >
                        <i class="fas fa-sync-alt mr-2" wire:loading.class="animate-spin"></i> 
                        <span wire:loading.remove>Refresh Now</span>
                        <span wire:loading>Refreshing...</span>
                    </button>
                </div>
            </div>
            <div class="flex flex-wrap mt-6 -mx-2">
                <div class="px-2 w-full sm:w-1/2 lg:w-1/4 mb-4 sm:mb-0">
                    <div class="bg-white rounded-lg p-4 shadow-sm flex items-center dashboard-stat-card">
                        <div class="rounded-full bg-[#f9b903] p-3 mr-3">
                            <i class="fas fa-percentage text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Occupancy Rate</p>
                            <p class="font-bold text-lg" data-track-changes data-track-id="occupancy-rate">
                                {{ $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100) : 0 }}%
                            </p>
                        </div>
                    </div>
                </div>
                <div class="px-2 w-full sm:w-1/2 lg:w-1/4 mb-4 sm:mb-0">
                    <div class="bg-white rounded-lg p-4 shadow-sm flex items-center dashboard-stat-card">
                        <div class="rounded-full bg-[#f9b903] p-3 mr-3">
                            <i class="fas fa-broom text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Clean Rate</p>
                            <p class="font-bold text-lg">{{ $totalRooms > 0 ? round(($cleanRooms / $totalRooms) * 100) : 0 }}%</p>
                        </div>
                    </div>
                </div>
                <div class="px-2 w-full sm:w-1/2 lg:w-1/4 mb-4 sm:mb-0">
                    <div class="bg-white rounded-lg p-4 shadow-sm flex items-center dashboard-stat-card">
                        <div class="rounded-full bg-[#f9b903] p-3 mr-3">
                            <i class="fas fa-calendar-check text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Today's Activity</p>
                            <p class="font-bold text-lg">{{ $todayActivity ?? 0 }}</p>
                        </div>
                    </div>
                </div>
                <div class="px-2 w-full sm:w-1/2 lg:w-1/4">
                    <div class="bg-white rounded-lg p-4 shadow-sm flex items-center dashboard-stat-card">
                        <div class="rounded-full bg-[#f9b903] p-3 mr-3">
                            <i class="fas fa-utensils text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Food Orders</p>
                            <p class="font-bold text-lg" id="dashboard-food-orders-count">0</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    

    @livewire('real-time-room-status')


    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">

        @livewire('cleaning-status-section')
        
        <livewire:real-time-checkin-checkout />
    </div>


    <livewire:real-time-food-status />
    
    <!-- Monthly Check-in/Check-out Chart -->
    <div class="bg-white rounded-lg shadow-sm p-5 mb-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-800">Monthly Check-in/Check-out Activity</h2>
            <button onclick="refreshMonthlyChart()" class="text-sm bg-white hover:bg-gray-50 text-gray-800 py-1 px-3 rounded border border-gray-200 flex items-center transition-all">
                <i class="fas fa-sync-alt mr-1.5 text-[#f9b903]"></i> Refresh Chart
            </button>
        </div>
        <div class="chart-container relative h-80">
            <canvas id="monthlyActivityChart"></canvas>
            <div id="chart-loading" class="absolute inset-0 bg-white bg-opacity-80 flex items-center justify-center hidden">
                <div class="w-10 h-10 border-4 border-[#f9b903] border-t-transparent rounded-full animate-spin"></div>
            </div>
        </div>
    </div>
    
    

    <div class="mt-10 bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="bg-gradient-to-r from-[#f9b903]/10 to-white p-6 border-t-4 border-[#f9b903]">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div class="mb-6 md:mb-0">
                    <div class="flex items-center mb-3">
                        <img src="{{ asset('favicon.svg') }}" alt="NovaERA Logo" class="h-10 w-auto mr-3">
                        <div>
                            <h3 class="font-semibold text-gray-800">NovaERA HMS</h3>
                            <p class="text-xs text-gray-500">Hotel Management Excellence</p>
                        </div>
                    </div>
                    <div class="flex items-center text-xs text-gray-500 mt-2">
                        <span class="flex items-center mr-3">
                            <i class="fas fa-code-branch mr-1 text-[#f9b903]"></i> Version 1.2.0
                        </span>
                        <span class="flex items-center">
                            <i class="fas fa-shield-alt mr-1 text-[#f9b903]"></i> Secure
                        </span>
                    </div>
                </div>
                
                <div class="flex flex-col md:flex-row gap-4 w-full md:w-auto">
                    <!-- System Status Card -->
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-100 w-full md:w-auto">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="font-medium text-sm text-gray-700">System Status</h4>
                            <div class="flex items-center">
                                <div class="w-2 h-2 rounded-full bg-green-500 mr-1.5 animate-pulse-green"></div>
                                <span class="text-xs text-green-600">Online</span>
                            </div>
                        </div>
                        
                        <div class="flex flex-col gap-2">
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-500 flex items-center">
                                    <i class="fas fa-clock mr-1.5 text-[#f9b903]"></i> Last updated
                                </span>
                                <span class="text-xs font-medium">{{ now()->format('H:i:s') }}</span>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-500 flex items-center">
                                    <i class="fas fa-sync-alt mr-1.5 text-[#f9b903]"></i> Auto-refresh
                                </span>
                                <span class="text-xs font-medium text-[#f9b903]">Active</span>
                            </div>
                            
                            <button onclick="@this.refreshDashboard()" class="mt-2 w-full bg-white hover:bg-gray-50 text-gray-800 text-xs py-1.5 px-3 rounded border border-gray-200 flex items-center justify-center transition-all">
                                <i class="fas fa-sync-alt mr-1.5 text-[#f9b903]"></i> Refresh
                                <kbd class="ml-2 bg-gray-100 px-1 py-0.5 text-xs rounded text-gray-700">Ctrl+R</kbd>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Quick Links -->
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-100 w-full md:w-auto">
                        <h4 class="font-medium text-sm text-gray-700 mb-3">Quick Links</h4>
                        <div class="grid grid-cols-2 gap-2">
                            <a href="#" class="text-xs text-gray-600 hover:text-[#f9b903] flex items-center">
                                <i class="fas fa-user-shield mr-1.5"></i> Admin Panel
                            </a>
                            <a href="#" class="text-xs text-gray-600 hover:text-[#f9b903] flex items-center">
                                <i class="fas fa-cog mr-1.5"></i> Settings
                            </a>
                            <a href="#" class="text-xs text-gray-600 hover:text-[#f9b903] flex items-center">
                                <i class="fas fa-question-circle mr-1.5"></i> Help Center
                            </a>
                            <a href="#" class="text-xs text-gray-600 hover:text-[#f9b903] flex items-center">
                                <i class="fas fa-chart-bar mr-1.5"></i> Reports
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Copyright Bar -->
            <div class="mt-6 pt-4 border-t border-gray-100 flex flex-col sm:flex-row justify-between items-center text-xs text-gray-500">
                <p>© {{ date('Y') }} NovaERA Hotel Management System. All rights reserved.</p>
                <div class="flex items-center mt-2 sm:mt-0">
                    <span class="flex items-center mx-2">
                        <i class="fas fa-code text-[#f9b903] mr-1"></i> with
                        <i class="fas fa-heart text-red-500 mx-1"></i> by NovaERA Team
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Dashboard Scripts -->
<script>
    // Global refresh function for the dashboard
    function refreshDashboard() {
        if (typeof @this !== 'undefined') {
            @this.refreshDashboard();
        }
    }
    
    // Monthly check-in/check-out chart
    // Make sure we don't redeclare the variable if it already exists in window scope
    if (!window.monthlyChart) {
        window.monthlyChart = null;
    }
    
    function initMonthlyChart() {
        // Destroy existing chart instance if it exists to prevent memory leaks
        if (window.monthlyChart) {
            window.monthlyChart.destroy();
        }
        
        const canvas = document.getElementById('monthlyActivityChart');
        if (!canvas) return null;
        
        const ctx = canvas.getContext('2d');
        
        window.monthlyChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [],
                datasets: [
                    {
                        label: 'Check-ins',
                        backgroundColor: '#3B82F6',
                        data: []
                    },
                    {
                        label: 'Check-outs',
                        backgroundColor: '#F59E0B',
                        data: []
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
        
        return monthlyChart;
    }
    
    function refreshMonthlyChart() {
        // Make sure we have the canvas element
        if (!document.getElementById('monthlyActivityChart')) {
            console.log('Monthly chart canvas not found, skipping refresh');
            return;
        }
        
        const loadingElement = document.getElementById('chart-loading');
        if (loadingElement) loadingElement.classList.remove('hidden');
        
        console.log('Refreshing monthly chart data...');
        
        // Get the data from the server or from the Livewire component
        if (typeof @this !== 'undefined') {
            @this.refreshDashboard().then(() => {
                // First try to get data from API
                fetch('/api/dashboard/monthly-activity')
                    .then(response => response.json())
                    .then(data => {
                        updateMonthlyChartWithData(data);
                        if (loadingElement) loadingElement.classList.add('hidden');
                    })
                    .catch(error => {
                        console.error('Error loading chart data:', error);
                        if (loadingElement) loadingElement.classList.add('hidden');
                    });
            });
        } else {
            // Fallback to direct API call if not in a Livewire context
            fetch('/api/dashboard/monthly-activity')
                .then(response => response.json())
                .then(data => {
                    updateMonthlyChartWithData(data);
                    if (loadingElement) loadingElement.classList.add('hidden');
                })
                .catch(error => {
                    console.error('Error loading chart data:', error);
                    if (loadingElement) loadingElement.classList.add('hidden');
                });
        }
    }
    
    function updateMonthlyChartWithData(data) {
        try {
            if (!window.monthlyChart || !document.getElementById('monthlyActivityChart')) {
                window.monthlyChart = initMonthlyChart();
            }
            
            if (window.monthlyChart) {
                window.monthlyChart.data.labels = data.map(item => item.month);
                window.monthlyChart.data.datasets[0].data = data.map(item => item.checkins);
                window.monthlyChart.data.datasets[1].data = data.map(item => item.checkouts);
                window.monthlyChart.update();
                console.log('Monthly chart updated with data:', data);
            }
        } catch (error) {
            console.error('Error updating monthly chart:', error);
        }
    }
    


    // Initialize charts once when the dashboard is first loaded
    document.addEventListener('livewire:initialized', () => {
        if (typeof @this !== 'undefined') {
            // Update time display
            const updateTime = new Date().toLocaleTimeString();
            if (document.getElementById('last-update-time')) {
                document.getElementById('last-update-time').textContent = updateTime;
            }
            
            // Use the central registry to initialize charts
            if (window.novaEraCharts && window.novaEraCharts.updateAll) {
                window.novaEraCharts.updateAll();
            }
            
            // Use our enhanced chart rendering function
            if (typeof window.ensureChartsRendered === 'function') {
                window.ensureChartsRendered();
            } else {
                // Backwards compatibility fallback
                if (typeof updateMonthlyTrendsChart === 'function') updateMonthlyTrendsChart();
                if (typeof updateRoomCategoryChart === 'function') updateRoomCategoryChart();
            }
        }
    });
    
    // This ensures charts are initialized only once when loading the dashboard
    window.dashboardChartsInitialized = false;
    
    // Debounce function to limit how often a function can run
    function debounce(func, wait) {
        let timeout;
        return function() {
            const context = this;
            const args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                func.apply(context, args);
            }, wait);
        };
    }
    
    // Safe chart initialization
    function safeInitChart(chartInitFn, chartId) {
        try {
            if (document.getElementById(chartId)) {
                return chartInitFn();
            }
        } catch (error) {
            console.error(`Error initializing chart ${chartId}:`, error);
        }
        return null;
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        // Add keyboard shortcut for refreshing dashboard (Ctrl+R)
        document.addEventListener('keydown', function(event) {
            if (event.ctrlKey && event.key === 'r') {
                event.preventDefault(); // Prevent browser refresh

            }
        });

        // Responsive handling for mobile devices
        const chartContainers = document.querySelectorAll('.chart-container');
        chartContainers.forEach(container => {
            // Touch events
            container.addEventListener('touchstart', function(e) {
                container.classList.add('active-touch');
            });
            
            container.addEventListener('touchend', function(e) {
                container.classList.remove('active-touch');
            });
            
            // Handle resize events to redraw charts
            const chartId = container.getAttribute('id');
            if (chartId && chartId.includes('chart')) {
                const resizeObserver = new ResizeObserver(debounce(() => {
                    const chartName = chartId.replace('chart-', '').replace(/-/g, '');
                    const updateFn = window['update' + chartName.charAt(0).toUpperCase() + chartName.slice(1) + 'Chart'];
                    if (typeof updateFn === 'function') {
                        updateFn();
                    }
                }, 250));
                
                resizeObserver.observe(container);
            }
        });
    });
    
    // Handle theme changes (light/dark mode)
    if (window.matchMedia) {
        const darkModeMediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
        const handleThemeChange = (e) => {
            const isDarkMode = e.matches;
            document.documentElement.classList.toggle('dark-theme', isDarkMode);
            window.novaEraCharts.updateAll();
        };
        
        // Initial check
        handleThemeChange(darkModeMediaQuery);
        
        // Add listener
        if (darkModeMediaQuery.addEventListener) {
            darkModeMediaQuery.addEventListener('change', handleThemeChange);
        }
    }

    // Global function to ensure all charts are initialized
    // This will be useful when navigating back to the dashboard
    window.reinitializeDashboardCharts = function() {
        console.log('Manually reinitializing all dashboard charts');
        
        // Wait for DOM to be ready
        setTimeout(() => {
            // Initialize our monthly activity chart
            if (document.getElementById('monthlyActivityChart')) {
                console.log('Reinitializing monthly activity chart');
                window.monthlyChart = initMonthlyChart();
                refreshMonthlyChart();
            }
        
            // Initialize Monthly Trends Chart (legacy)
            if (typeof window.initMonthlyTrendsChart === 'function' && 
                typeof window.refreshMonthlyTrendsChart === 'function' && 
                document.getElementById('monthlyTrendsChart')) {
                
                console.log('Reinitializing monthly trends chart');
                window.monthlyTrendsChart = window.monthlyTrendsChart || window.initMonthlyTrendsChart();
                if (window.monthlyTrendsChart) {
                    window.refreshMonthlyTrendsChart();
                }
            }
            
            // Initialize Room Category Chart
            if (typeof window.initRoomCategoryChart === 'function' && 
                typeof window.refreshRoomCategoryChart === 'function' && 
                document.getElementById('roomCategoryChart')) {
                
                console.log('Reinitializing room category chart');
                window.roomCategoryChart = window.roomCategoryChart || window.initRoomCategoryChart();
                if (window.roomCategoryChart) {
                    window.refreshRoomCategoryChart();
                }
            }
            
            // Registry-based initialization
            if (window.novaEraCharts && window.novaEraCharts.updateAll) {
                window.novaEraCharts.updateAll();
            }
        }, 300);
    };

    // Listen for route/navigation changes in Laravel/Livewire
    document.addEventListener('livewire:navigated', function() {
        console.log('Livewire navigation detected in main dashboard component, reinitializing charts');
        
        // Check if we're on the dashboard page
        const isDashboardPage = window.location.pathname.endsWith('/dashboard') || 
                               window.location.pathname === '/' ||
                               document.querySelector('.dashboard-container') !== null;
                               
        if (!isDashboardPage) {
            console.log('Not on dashboard page, skipping chart reinitialization');
            return;
        }
        
        console.log('On dashboard page, proceeding with chart reinitialization');
        
        // Check for monthly chart
        setTimeout(() => {
            if (document.getElementById('monthlyActivityChart')) {
                window.monthlyChart = initMonthlyChart();
                refreshMonthlyChart();
                console.log('Monthly activity chart reinitialized after navigation');
            }
        }, 200);
        
        // Force immediate chart initialization for other charts
        if (typeof window.reinitializeDashboardCharts === 'function') {
            window.reinitializeDashboardCharts();
        }
        
        // Then force data reload (with different timing to avoid race conditions)
        setTimeout(() => {
            if (typeof @this !== 'undefined') {
                console.log('Reloading dashboard data after navigation');
                @this.loadDashboardData().then(() => {
                    console.log('Dashboard data loaded, dispatching update event');
                    // In Livewire v3, the event will be dispatched by the component method
                    
                    // Try reinitialization again after data is loaded
                    if (typeof window.reinitializeDashboardCharts === 'function') {
                        window.reinitializeDashboardCharts();
                    }
                });
            }
        }, 200);
    });

    // On page visibility change (user returns to tab)
    document.addEventListener('visibilitychange', function() {
        if (document.visibilityState === 'visible') {
            console.log('Page visibility changed to visible, checking charts');
            
            // Check if we're on the dashboard page
            const isDashboardPage = window.location.pathname.endsWith('/dashboard') || 
                                   window.location.pathname === '/' ||
                                   document.querySelector('.dashboard-container') !== null;
                                   
            if (!isDashboardPage) return;
            
            // First update charts with existing data
            if (typeof window.reinitializeDashboardCharts === 'function') {
                window.reinitializeDashboardCharts();
            }
            
            // Make sure our monthly chart is refreshed
            if (document.getElementById('monthlyActivityChart')) {
                console.log('Refreshing monthly activity chart after tab visibility changed');
                refreshMonthlyChart();
            }
            
            // Then trigger an explicit chart update via Livewire
            setTimeout(() => {
                if (typeof @this !== 'undefined') {
                    console.log('Triggering explicit chart update');
                    if (typeof @this.updateCharts === 'function') {
                        @this.updateCharts();
                    } else {
                        @this.refreshDashboard();
                    }
                }
            }, 300);
        }
    });
    
    // Track previous values to detect changes
    window.previousValues = {};
    
    // Function to highlight elements that have changed
    function highlightChangedValues() {
        // Get all numeric values that might change
        const numericElements = document.querySelectorAll('[data-track-changes]');
        
        numericElements.forEach(el => {
            const key = el.dataset.trackId || el.textContent;
            const currentValue = el.textContent.trim();
            
            // If we have a previous value and it's different
            if (window.previousValues[key] && window.previousValues[key] !== currentValue) {
                // Add highlight class
                el.classList.add('highlight-change');
                
                // Remove highlight class after animation
                setTimeout(() => {
                    el.classList.remove('highlight-change');
                }, 2000);
            }
            
            // Store current value for next comparison
            window.previousValues[key] = currentValue;
        });
    }
    
    // Call this function on every Livewire update
    // Removed real-time update event listener
    
    // Function to flash/highlight an element by ID or the element itself
    function flashElement(elementOrId) {
        const element = typeof elementOrId === 'string' ? document.getElementById(elementOrId) : elementOrId;
        
        if (element) {
            // Add highlight class
            element.classList.add('highlight-change');
            
            // Remove highlight class after animation
            setTimeout(() => {
                element.classList.remove('highlight-change');
            }, 2000);
        }
    }
    
    // Function to highlight elements by class
    function highlightElementsByClass(className) {
        const elements = document.getElementsByClassName(className);
        Array.from(elements).forEach(el => flashElement(el));
    }
    
    // Function to update the cleaning gauge dynamically
    function updateCleaningGauge() {
        // Get the latest values from the dashboard component
        if (typeof @this !== 'undefined') {
            @this.refreshDashboard().then(() => {
                const gaugeElement = document.querySelector('path[stroke="url(#cleanGradient)"]');
                const cleanPercentElement = document.getElementById('clean-percentage');
                
                if (gaugeElement && cleanPercentElement) {
                    // Get the latest data
                    const totalRooms = parseInt(@this.totalRooms) || 1;
                    const cleanRooms = parseInt(@this.cleanRooms) || 0;
                    const cleanPercent = Math.round((cleanRooms / totalRooms) * 100);
                    
                    // Update the gauge visual
                    gaugeElement.setAttribute('stroke-dasharray', `${cleanPercent}, 100`);
                    
                    // Update the percentage text
                    cleanPercentElement.textContent = cleanPercent;
                    
                    // Flash the updated elements
                    flashElement(gaugeElement);
                    flashElement(cleanPercentElement);
                    
                    console.log('Cleaning gauge updated with values:', {
                        totalRooms,
                        cleanRooms,
                        cleanPercent
                    });
                }
            });
        }
    }
    
    // Add CSS for highlight animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes highlight-pulse {
            0% { background-color: rgba(249, 185, 3, 0); }
            50% { background-color: rgba(249, 185, 3, 0.2); }
            100% { background-color: rgba(249, 185, 3, 0); }
        }
        .highlight-change {
            animation: highlight-pulse 2s ease-in-out;
        }
        
        @keyframes stroke-highlight {
            0% { stroke-width: 4; }
            50% { stroke-width: 6; }
            100% { stroke-width: 4; }
        }
        path.highlight-change {
            animation: stroke-highlight 2s ease-in-out;
        }
    `;
    document.head.appendChild(style);
</script>

<!-- Dashboard highlighting functions for manual refresh -->
<script>
    // Function to highlight status elements when they change
    function highlightStatusElements(className) {
        const elements = document.getElementsByClassName(className);
        if (elements.length > 0) {
            Array.from(elements).forEach(el => {
                // Add highlight class
                el.classList.add('highlight-change');
                
                // Remove highlight class after animation
                setTimeout(() => {
                    el.classList.remove('highlight-change');
                }, 2000);
            });
        }
    }
</script>

<!-- Dashboard initialization -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Basic dashboard initialization
        console.log('Dashboard initialized at', new Date().toISOString());
        
        // Initialize the monthly chart
        if (document.getElementById('monthlyActivityChart')) {
            window.monthlyChart = initMonthlyChart();
            refreshMonthlyChart();
        }
        
        // Ensure charts are initialized and loaded with data
        setTimeout(() => {
            // First try our new ensureChartsRendered function
            if (typeof window.ensureChartsRendered === 'function') {
                window.ensureChartsRendered();
            } 
            // Fallback to previous method
            else if (typeof window.reinitializeDashboardCharts === 'function') {
                window.reinitializeDashboardCharts();
            }
        }, 500);
    });
    
    // Add a mutation observer to watch for dashboard content changes
    // This helps with single-page application navigation
    if (!window.dashboardObserverInitialized) {
        window.dashboardObserver = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.addedNodes.length > 0) {
                    // Check if charts need initialization
                    const chartCanvases = document.querySelectorAll('canvas[id$="Chart"]');
                    if (chartCanvases.length > 0) {
                        console.log('Chart canvases detected in DOM, ensuring initialization');
                        if (typeof window.reinitializeDashboardCharts === 'function') {
                            window.reinitializeDashboardCharts();
                        }
                    }
                }
            });
        });
        window.dashboardObserverInitialized = true;
    }
    
    // Start observing once DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        const dashboardContainer = document.getElementById('dashboard-main-container');
        if (dashboardContainer && window.dashboardObserver) {
            window.dashboardObserver.observe(dashboardContainer, { 
                childList: true, 
                subtree: true 
            });
        }
    });
</script>
