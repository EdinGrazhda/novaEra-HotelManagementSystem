/**
 * Room Color Manager - Centralizes room color management
 * This script ensures room background colors are based on room status only,
 * not cleaning status, and prevents performance issues from multiple scripts
 * trying to manage colors simultaneously.
 */

// Wait for DOM to be fully loaded
document.addEventListener("DOMContentLoaded", function () {
    // Single source of truth for room status colors
    const roomStatusColors = {
        available: {
            background: "#ECFDF5",
            border: "#10B981",
        },
        occupied: {
            background: "#FEF2F2",
            border: "#EF4444",
        },
        maintenance: {
            background: "#F9FAFB",
            border: "#6B7280",
        },
    };

    // Function to apply room colors based on status
    function applyRoomColors() {
        document.querySelectorAll(".room-box").forEach((roomBox) => {
            const roomStatus = roomBox.getAttribute("data-status");

            if (roomStatusColors[roomStatus]) {
                roomBox.style.backgroundColor =
                    roomStatusColors[roomStatus].background;
                roomBox.style.borderColor = roomStatusColors[roomStatus].border;
            }
        });
    }

    // Apply colors on page load
    applyRoomColors();

    // Debounce function to prevent excessive executions
    function debounce(func, wait) {
        let timeout;
        return function () {
            const context = this;
            const args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(context, args), wait);
        };
    }

    // Debounced version of applyRoomColors
    const debouncedApplyRoomColors = debounce(applyRoomColors, 150);

    // Monitor DOM changes with lightweight approach (no full MutationObserver)
    document.addEventListener("roomStatusChanged", debouncedApplyRoomColors);

    // Handle AJAX updates for jQuery if available
    if (typeof jQuery !== "undefined") {
        jQuery(document).on("ajaxComplete", debouncedApplyRoomColors);
    }

    // Export the function for other scripts to use
    window.applyRoomColors = debouncedApplyRoomColors;
});
