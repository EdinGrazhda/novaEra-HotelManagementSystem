// This script ensures the site defaults to light mode
document.addEventListener("DOMContentLoaded", function () {
    // Check for saved theme preference - if none exists, set to light
    let savedTheme = localStorage.getItem("theme");

    if (!savedTheme) {
        // Remove dark class from html tag
        document.documentElement.classList.remove("dark");
        document.documentElement.classList.remove("dark-theme");

        // Store the preference in localStorage
        localStorage.setItem("theme", "light");

        console.log("Light mode set as default");
    } else {
        // If a theme preference exists, respect it
        if (savedTheme === "light") {
            document.documentElement.classList.remove("dark");
            document.documentElement.classList.remove("dark-theme");
        }
    }

    // Update chart colors if they exist
    if (window.novaEraCharts && window.novaEraCharts.updateAll) {
        window.novaEraCharts.updateAll();
    }
});
