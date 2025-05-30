// This script ensures cleaning status changes don't affect room card background colors
document.addEventListener("DOMContentLoaded", function () {
    // Function to apply the correct color based on room status only
    function applyRoomStatusColors() {
        // Apply to all room boxes - on both index and show pages
        document
            .querySelectorAll(".room-box, .room-details")
            .forEach((roomElement) => {
                const roomStatus = roomElement.getAttribute("data-status");

                // Apply color based on room status only, ignoring cleaning status
                if (roomStatus === "available") {
                    roomElement.style.backgroundColor = "#ECFDF5";
                    roomElement.style.borderColor = "#10B981";
                } else if (roomStatus === "occupied") {
                    roomElement.style.backgroundColor = "#FEF2F2";
                    roomElement.style.borderColor = "#EF4444";
                } else if (roomStatus === "maintenance") {
                    roomElement.style.backgroundColor = "#F9FAFB";
                    roomElement.style.borderColor = "#6B7280";
                }
            });
    }

    // Apply colors immediately when page loads
    applyRoomStatusColors();

    // Attach event listeners to cleaning status update forms
    document
        .querySelectorAll('form[action*="updateCleaningStatus"]')
        .forEach((form) => {
            form.addEventListener("submit", function (e) {
                // For AJAX forms - we would need to prevent default and handle it,
                // but for normal form submissions the colors will be reapplied on page reload
                const roomCard = this.closest(".room-box, .room-details");

                if (roomCard) {
                    // Mark this form as being for cleaning status only
                    localStorage.setItem(
                        "lastActionType",
                        "cleaningStatusUpdate"
                    );
                    localStorage.setItem(
                        "lastRoomStatus",
                        roomCard.getAttribute("data-status")
                    );
                }
            });
        });

    // If we just did a cleaning status update (page reload), ensure colors are correct
    if (localStorage.getItem("lastActionType") === "cleaningStatusUpdate") {
        applyRoomStatusColors();
        localStorage.removeItem("lastActionType");
        localStorage.removeItem("lastRoomStatus");
    }
});
