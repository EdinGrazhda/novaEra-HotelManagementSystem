// NovaEra Charts Registry
// This script manages Chart.js instances across the application
window.novaEraCharts = (function () {
    const chartRegistry = new Map();

    return {
        // Register a chart instance
        register: function (chartId, chartInstance) {
            chartRegistry.set(chartId, chartInstance);
            console.log(`Chart registered: ${chartId}`);
            return chartInstance;
        },

        // Get a chart instance by ID
        getChart: function (chartId) {
            return chartRegistry.get(chartId);
        },

        // Remove a chart from the registry
        unregister: function (chartId) {
            if (chartRegistry.has(chartId)) {
                chartRegistry.delete(chartId);
                console.log(`Chart unregistered: ${chartId}`);
                return true;
            }
            return false;
        },

        // Get all registered chart IDs
        getAllChartIds: function () {
            return Array.from(chartRegistry.keys());
        },
    };
})();
