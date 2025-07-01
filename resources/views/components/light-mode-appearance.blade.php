<!-- Light Mode Appearance -->
@fluxAppearance

<!-- Script to ensure Light Mode by default -->
<script>
// Immediate enforcement of light mode
(function() {
    document.documentElement.classList.remove("dark");
    document.documentElement.classList.add("light");
    localStorage.setItem("theme", "light");
})();

// Continue enforcing light mode when DOM loads
document.addEventListener("DOMContentLoaded", function () {
    // Force light mode
    document.documentElement.classList.remove("dark");
    document.documentElement.classList.add("light");
    
    // Store the preference in localStorage
    localStorage.setItem("theme", "light");
    
    // Override the Flux appearance system
    if (window.$flux && window.$flux.appearance) {
        window.$flux.appearance = 'light';
    }
    
    // Force refresh any components that might depend on the theme
    if (window.Livewire) {
        window.Livewire.dispatch('theme-changed', { theme: 'light' });
    }
    
    // Add a mutation observer to keep enforcing light mode
    const observer = new MutationObserver(function(mutations) {
        if (document.documentElement.classList.contains('dark')) {
            document.documentElement.classList.remove('dark');
            document.documentElement.classList.add('light');
        }
    });
    
    observer.observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['class']
    });
});
</script>
