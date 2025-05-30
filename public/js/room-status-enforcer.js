// This script enforces room background colors strictly based on room status only
document.addEventListener("DOMContentLoaded", function () {
    // Force room colors to be based on room status only
    function enforceRoomStatusColors() {
        document.querySelectorAll(".room-box").forEach((roomBox) => {
            const roomStatus = roomBox.getAttribute("data-status");

            // Apply color based on room status only
            if (roomStatus === "available") {
                roomBox.style.backgroundColor = "#ECFDF5";
                roomBox.style.borderColor = "#10B981";
            } else if (roomStatus === "occupied") {
                roomBox.style.backgroundColor = "#FEF2F2";
                roomBox.style.borderColor = "#EF4444";
            } else if (roomStatus === "maintenance") {
                roomBox.style.backgroundColor = "#F9FAFB";
                roomBox.style.borderColor = "#6B7280";
            }
        });
    }

    // Run on page load
    enforceRoomStatusColors();

    // Add event listener for AJAX complete if it exists
    if (typeof jQuery !== "undefined") {
        jQuery(document).on("ajaxComplete", enforceRoomStatusColors);
    }
});
