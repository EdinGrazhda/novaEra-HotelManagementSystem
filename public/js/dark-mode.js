// Dark mode management for all sidebar pages
document.addEventListener("DOMContentLoaded", function () {
    // Check for saved theme preference or use system preference
    let savedTheme = localStorage.getItem("theme");
    const systemPrefersDark = window.matchMedia(
        "(prefers-color-scheme: dark)"
    ).matches;

    // Function to set the theme
    function setTheme(isDark) {
        if (isDark) {
            document.documentElement.classList.add("dark");
            document.documentElement.classList.add("dark-theme");
        } else {
            document.documentElement.classList.remove("dark");
            document.documentElement.classList.remove("dark-theme");
        }

        // Store preference
        localStorage.setItem("theme", isDark ? "dark" : "light");

        // Update chart colors if charts exist
        if (window.novaEraCharts && window.novaEraCharts.updateAll) {
            window.novaEraCharts.updateAll();
        }
    }

    // Set initial theme based on saved preference or system preference
    if (savedTheme) {
        setTheme(savedTheme === "dark");
    } else {
        setTheme(systemPrefersDark);
    }

    // Listen for changes to system preference
    window
        .matchMedia("(prefers-color-scheme: dark)")
        .addEventListener("change", (e) => {
            // Only switch to system preference if user hasn't manually selected a theme
            if (
                localStorage.getItem("theme") === "system" ||
                !localStorage.getItem("theme")
            ) {
                setTheme(e.matches);
            }
        });

    // Listen for Flux theme changes from the appearance settings
    document.addEventListener("fluxThemeChange", (event) => {
        const theme = event.detail.theme;

        if (theme === "dark") {
            setTheme(true);
        } else if (theme === "light") {
            setTheme(false);
        } else if (theme === "system") {
            localStorage.setItem("theme", "system");
            setTheme(window.matchMedia("(prefers-color-scheme: dark)").matches);
        }
    });

    // Add a toggle button to the navbar for testing (if needed)
    const addToggleButton = false; // Set to true if you want a test button
    if (addToggleButton) {
        const navbar =
            document.querySelector("nav") || document.querySelector("header");
        if (navbar) {
            const toggleButton = document.createElement("button");
            toggleButton.innerText = "🌓";
            toggleButton.style.margin = "0 10px";
            toggleButton.style.padding = "5px 10px";
            toggleButton.style.borderRadius = "5px";
            toggleButton.style.background = "#f9b903";
            toggleButton.style.border = "none";
            toggleButton.style.cursor = "pointer";

            toggleButton.addEventListener("click", () => {
                const isDark =
                    document.documentElement.classList.contains("dark");
                setTheme(!isDark);
            });

            navbar.appendChild(toggleButton);
        }
    }
});
