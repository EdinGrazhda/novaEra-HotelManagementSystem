<x-layouts.app :title="__('Hotel Management Dashboard')">
    <livewire:dashboard />

    {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Dashboard Scripts -->
<script>
    // Global refresh function for the dashboard
    function refreshDashboard() {
        if (typeof @this !== 'undefined') {
            @this.refreshDashboard();
        }
    }
    
    // Monthly check-in/check-out chart
    let monthlyChart = null;
    
    function initMonthlyChart() {
        const ctx = document.getElementById('monthlyActivityChart').getContext('2d');
        
        monthlyChart = new Chart(ctx, {
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
        const loadingElement = document.getElementById('chart-loading');
        if (loadingElement) loadingElement.classList.remove('hidden');
        
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
        if (!monthlyChart) {
            monthlyChart = initMonthlyChart();
        }
        
        monthlyChart.data.labels = data.map(item => item.month);
        monthlyChart.data.datasets[0].data = data.map(item => item.checkins);
        monthlyChart.data.datasets[1].data = data.map(item => item.checkouts);
        monthlyChart.update();
        
        console.log('Monthly chart updated with data:', data);
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
            // Initialize Monthly Trends Chart
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
        
        // Force immediate chart initialization
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
            
            // Then trigger an explicit chart update via Livewire
            setTimeout(() => {
                if (typeof @this !== 'undefined') {
                    console.log('Triggering explicit chart update');
                    @this.updateCharts();
                    
                    // In Livewire v3, the event will be dispatched by the component method
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
            monthlyChart = initMonthlyChart();
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
--}}
</x-layouts.app> 
