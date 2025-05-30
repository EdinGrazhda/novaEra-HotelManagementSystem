/**
 * Cleaning Page Functionality
 * This script handles the cleaning status updates specifically for the cleaning page
 */
document.addEventListener("DOMContentLoaded", function () {
    console.log("cleaning-page.js loaded - DOM Content Loaded event fired");

    // The cleaning-status-sync.js will now handle button initialization
    // This script is kept for backwards compatibility

    // Only initialize handlers if cleaning-status-sync.js isn't loaded
    if (!window.cleaningStatusSyncInitialized) {
        console.log(
            "Initializing cleaning page functionality from cleaning-page.js"
        );

        // Get all cleaning status buttons
        const cleanStatusButtons =
            document.querySelectorAll(".clean-status-btn");

        // Get all cleaning notes inputs
        const cleaningNotes = document.querySelectorAll(".cleaning-notes");

        console.log(
            `Found ${cleanStatusButtons.length} status buttons in cleaning-page.js`
        );
        console.log(
            `Found ${cleaningNotes.length} notes inputs in cleaning-page.js`
        );

        // Add click handler to each cleaning status button
        cleanStatusButtons.forEach((button) => {
            button.addEventListener("click", function () {
                const roomId = this.getAttribute("data-room");
                const status = this.getAttribute("data-status");
                const roomBox = this.closest(".room-box");
                const notesInput = roomBox.querySelector(".cleaning-notes");
                const notes = notesInput ? notesInput.value : "";

                console.log(
                    `Clicking button to update room ${roomId} to ${status}`
                );

                // Update visual status immediately
                updateCleaningStatusVisually(roomBox, status);

                // Send AJAX request
                updateCleaningStatusAjax(roomId, status, notes);
            });
        });

        // Add event listeners to cleaning notes inputs
        cleaningNotes.forEach((input) => {
            // Save on blur (when user clicks outside)
            input.addEventListener("blur", function () {
                const roomId = this.getAttribute("data-room");
                const roomBox = this.closest(".room-box");
                const status = roomBox.getAttribute("data-cleaning");
                const notes = this.value;

                // Only send if notes have changed
                if (notes !== this.dataset.originalValue) {
                    updateCleaningStatusAjax(roomId, status, notes);
                    this.dataset.originalValue = notes;
                }
            });

            // Save on Enter key press
            input.addEventListener("keypress", function (event) {
                if (event.key === "Enter") {
                    event.preventDefault(); // Prevent form submission
                    this.blur(); // Trigger the blur event handler
                }
            }); // Store original value
            input.dataset.originalValue = input.value;
        });
    }

    // Function to update cleaning status visually
    function updateCleaningStatusVisually(roomBox, status) {
        // Update the data-cleaning attribute
        roomBox.setAttribute("data-cleaning", status);

        // Update button styling
        const buttons = roomBox.querySelectorAll(".clean-status-btn");
        buttons.forEach((btn) => {
            btn.classList.remove(
                "border-2",
                "border-green-500",
                "border-red-500",
                "border-yellow-500"
            );
        });

        // Add border to the clicked button
        const activeButton = roomBox.querySelector(
            `.clean-status-btn[data-status="${status}"]`
        );
        if (activeButton) {
            if (status === "clean") {
                activeButton.classList.add("border-2", "border-green-500");
            } else if (status === "not_cleaned") {
                activeButton.classList.add("border-2", "border-red-500");
            } else {
                // in_progress
                activeButton.classList.add("border-2", "border-yellow-500");
            }
        }

        // Update the status badge
        const statusBadge = roomBox.querySelector(".room-status-badge");
        if (statusBadge) {
            statusBadge.textContent =
                status === "not_cleaned"
                    ? "Not Cleaned"
                    : status === "in_progress"
                    ? "In Progress"
                    : "Clean";

            statusBadge.className =
                "font-medium room-status-badge " +
                (status === "clean"
                    ? "bg-green-100 text-green-800"
                    : status === "not_cleaned"
                    ? "bg-red-100 text-red-800"
                    : "bg-yellow-100 text-yellow-800");
        }
    }

    // Function to update cleaning status via AJAX
    function updateCleaningStatusAjax(roomId, status, notes) {
        // Create and show spinner
        const roomBox = document.querySelector(
            `.room-box[data-room="${roomId}"]`
        );
        let spinner = null;

        if (roomBox) {
            spinner = document.createElement("div");
            spinner.className =
                "absolute top-2 right-2 animate-spin h-5 w-5 border-2 border-blue-500 border-t-transparent rounded-full";
            spinner.id = `spinner-${roomId}`;
            roomBox.style.position = "relative";
            roomBox.appendChild(spinner);
        }

        // Get the CSRF token
        const csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content");

        // Create form data
        const formData = new FormData();
        formData.append("cleaning_status", status);
        formData.append("cleaning_notes", notes);
        formData.append("_method", "PATCH");
        formData.append("_token", csrfToken);

        // Make AJAX request
        fetch(`/cleaning/${roomId}/update-status`, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
                Accept: "application/json",
            },
            body: formData,
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then((data) => {
                if (data.success) {
                    console.log("Cleaning status updated successfully:", data);

                    // Show success notification
                    showNotification("Status updated successfully", "success");

                    // Store the update for cross-page synchronization
                    if (window.storeCleaningStatusUpdate) {
                        window.storeCleaningStatusUpdate(roomId, status, notes);
                    }

                    // Make sure the room color is maintained based on room status
                    if (window.applyRoomColors) {
                        window.applyRoomColors();
                    }
                } else {
                    console.error("Error updating cleaning status:", data);
                    showNotification("Failed to update status", "error");
                }
            })
            .catch((error) => {
                console.error("Error:", error);
                showNotification("An error occurred", "error");
            })
            .finally(() => {
                // Remove spinner
                if (spinner) {
                    spinner.remove();
                }
            });
    }

    // Function to show a notification
    function showNotification(message, type = "success") {
        // Create notification element
        const notification = document.createElement("div");
        notification.className = `fixed bottom-4 right-4 px-4 py-2 rounded-lg shadow-lg transition-opacity duration-500 ${
            type === "success"
                ? "bg-green-500 text-white"
                : "bg-red-500 text-white"
        }`;
        notification.style.zIndex = "9999";
        notification.textContent = message;

        // Add to body
        document.body.appendChild(notification);

        // Remove after 3 seconds
        setTimeout(() => {
            notification.classList.add("opacity-0");
            setTimeout(() => {
                notification.remove();
            }, 500);
        }, 3000);
    }
});
