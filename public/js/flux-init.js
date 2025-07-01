// Initialize flux theme to light mode
document.addEventListener("DOMContentLoaded", function () {
    // This runs once Alpine is initialized and the $flux object is available
    document.addEventListener("alpine:init", () => {
        // Set default appearance to light if Flux is available
        if (
            window.Alpine &&
            window.Alpine.store &&
            window.Alpine.store("flux")
        ) {
            window.Alpine.store("flux").appearance = "light";

            // Store the preference in localStorage
            localStorage.setItem("theme", "light");

            // Make sure the UI reflects this
            document.documentElement.classList.remove("dark");
            document.documentElement.classList.remove("dark-theme");

            console.log("Flux appearance set to light mode");
        }
    });
});
