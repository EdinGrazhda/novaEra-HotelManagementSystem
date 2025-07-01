<!-- Light Mode Enforcer -->
<script>
    // Immediate enforcement - runs before page renders
    document.documentElement.classList.remove('dark');
    document.documentElement.classList.add('light');
    localStorage.setItem('theme', 'light');
    
    // Override system preference
    if (window.matchMedia) {
        window.matchMedia('(prefers-color-scheme: dark)').removeEventListener('change', () => {});
    }
</script>
