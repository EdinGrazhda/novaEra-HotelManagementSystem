/**
 * Cleaning Status Sync - Ensures cleaning status is consistent across pages
 * This script uses localStorage to maintain cleaning status state between page navigations
 */

document.addEventListener("DOMContentLoaded", function () {
    // Set global flag that sync is initialized
    window.cleaningStatusSyncInitialized = true;
    // Initialize the sync mechanism
    initCleaningStatusSync();

    /**
     * Initialize the cleaning status synchronization mechanism
     */
    function initCleaningStatusSync() {
        console.log("Initializing cleaning status sync mechanism");

        // Check if the page needs a refresh by looking at the URL and navigation history
        checkIfPageNeedsRefresh();

        // Check if we're on the cleaning page and reinitialize the button handlers
        if (document.querySelectorAll(".clean-status-btn").length > 0) {
            reinitializeCleaningButtons();
        }

        // Check for any pending updates in localStorage
        checkAndApplyPendingUpdates();

        // Listen for storage events (when another tab/page updates localStorage)
        window.addEventListener("storage", function (e) {
            if (e.key && e.key.startsWith("room_cleaning_status_")) {
                applyCleaningStatusFromStorage(e.key, e.newValue);
            }
        });

        // Hook into existing AJAX updates to store changes
        hookIntoCleaningStatusUpdates();

        // Handle page unload to clean up old entries
        window.addEventListener("beforeunload", function () {
            cleanupOldStorageEntries();
        });
    }

    /**
     * Check localStorage for any pending updates and apply them
     */
    function checkAndApplyPendingUpdates() {
        // Get all localStorage keys
        for (let i = 0; i < localStorage.length; i++) {
            const key = localStorage.key(i);
            // Find cleaning status entries
            if (key && key.startsWith("room_cleaning_status_")) {
                const value = localStorage.getItem(key);
                applyCleaningStatusFromStorage(key, value);
            }
        }
    }

    /**
     * Apply a cleaning status update from localStorage
     */
    function applyCleaningStatusFromStorage(key, value) {
        try {
            // Extract room ID from the key
            const roomId = key.replace("room_cleaning_status_", "");

            if (!roomId) return;

            // Parse the stored data
            const data = JSON.parse(value);
            if (!data || !data.status) return;

            // Find the room box for this room
            const roomBox = document.querySelector(
                `.room-box[data-room="${roomId}"]`
            );
            if (!roomBox) return;

            console.log(
                `Sync: Updating room ${roomId} cleaning status to ${data.status}`
            );

            // Update the data attribute
            roomBox.setAttribute("data-cleaning", data.status);

            // Update the badge if it exists
            const statusBadge =
                roomBox.querySelector(".room-status-badge") ||
                roomBox.querySelector(".room-cleaning-badge");
            if (statusBadge) {
                const displayStatus =
                    data.status === "not_cleaned"
                        ? "Not Cleaned"
                        : data.status === "in_progress"
                        ? "In Progress"
                        : "Clean";

                statusBadge.textContent = displayStatus;

                // Update badge styling if needed
                statusBadge.className =
                    "font-medium room-status-badge " +
                    (data.status === "clean"
                        ? "bg-green-100 text-green-800"
                        : data.status === "not_cleaned"
                        ? "bg-red-100 text-red-800"
                        : "bg-yellow-100 text-yellow-800");
            }

            // Update cleaning notes if available
            if (data.notes) {
                const notesInput = roomBox.querySelector(".cleaning-notes");
                if (notesInput) {
                    notesInput.value = data.notes;
                    if (notesInput.dataset) {
                        notesInput.dataset.originalValue = data.notes;
                    }
                }
            }

            // Update button styling on the cleaning page
            const buttons = roomBox.querySelectorAll(".clean-status-btn");
            if (buttons.length > 0) {
                // Reset all button styles
                buttons.forEach((btn) => {
                    btn.classList.remove(
                        "border-2",
                        "border-green-500",
                        "border-red-500",
                        "border-yellow-500"
                    );
                });

                // Apply style to active button
                const activeButton = roomBox.querySelector(
                    `.clean-status-btn[data-status="${data.status}"]`
                );
                if (activeButton) {
                    if (data.status === "clean") {
                        activeButton.classList.add(
                            "border-2",
                            "border-green-500"
                        );
                    } else if (data.status === "not_cleaned") {
                        activeButton.classList.add(
                            "border-2",
                            "border-red-500"
                        );
                    } else {
                        // in_progress
                        activeButton.classList.add(
                            "border-2",
                            "border-yellow-500"
                        );
                    }
                }
            }

            // Make sure room colors are maintained
            if (window.applyRoomColors) {
                window.applyRoomColors();
            }

            // Remove the item from storage after it's been applied
            // to avoid reapplying it unnecessarily
            localStorage.removeItem(key);
        } catch (error) {
            console.error(
                "Error applying cleaning status from storage:",
                error
            );
        }
    }

    /**
     * Hook into existing cleaning status update mechanisms
     */
    function hookIntoCleaningStatusUpdates() {
        // Create a hook function that will intercept successful AJAX updates
        window.storeCleaningStatusUpdate = function (roomId, status, notes) {
            if (!roomId || !status) return;

            console.log(
                `Storing cleaning status update: Room ${roomId}, Status: ${status}`
            );

            // Store the update in localStorage with a timestamp to facilitate cleanup
            const storageKey = `room_cleaning_status_${roomId}`;
            const storageValue = JSON.stringify({
                status: status,
                notes: notes || "",
                timestamp: Date.now(),
            });

            localStorage.setItem(storageKey, storageValue);
        };

        // Find any fetch-based cleaning status updates in the page and hook into them
        const originalFetch = window.fetch;
        window.fetch = function (url, options) {
            const fetchPromise = originalFetch.apply(this, arguments);

            // Check if this is a cleaning status update request
            if (
                url &&
                typeof url === "string" &&
                url.includes("/cleaning/") &&
                url.includes("/update-status")
            ) {
                fetchPromise.then((response) => {
                    if (response.ok) {
                        // Extract the room ID from the URL
                        const urlParts = url.split("/");
                        const roomIdIndex = urlParts.indexOf("cleaning") + 1;
                        if (roomIdIndex > 0 && roomIdIndex < urlParts.length) {
                            const roomId = urlParts[roomIdIndex];

                            // Get the status and notes from the request body if possible
                            let status = "";
                            let notes = "";

                            if (
                                options &&
                                options.body &&
                                options.body instanceof FormData
                            ) {
                                status = options.body.get("cleaning_status");
                                notes = options.body.get("cleaning_notes");
                            }

                            // Store the update
                            window.storeCleaningStatusUpdate(
                                roomId,
                                status,
                                notes
                            );
                        }
                    }
                    return response;
                });
            }

            return fetchPromise;
        };
    }

    /**
     * Clean up old storage entries to avoid memory leaks
     */
    function cleanupOldStorageEntries() {
        // Set a threshold of 1 hour for cleanup
        const ONE_HOUR = 60 * 60 * 1000; // in milliseconds
        const now = Date.now();

        for (let i = 0; i < localStorage.length; i++) {
            const key = localStorage.key(i);
            if (key && key.startsWith("room_cleaning_status_")) {
                try {
                    const value = localStorage.getItem(key);
                    const data = JSON.parse(value);

                    if (
                        data &&
                        data.timestamp &&
                        now - data.timestamp > ONE_HOUR
                    ) {
                        localStorage.removeItem(key);
                    }
                } catch (e) {
                    // If there's an error parsing, just remove the item
                    localStorage.removeItem(key);
                }
            }
        }
    }

    /**
     * Reinitialize all cleaning status buttons
     * This ensures button click handlers are properly attached even when
     * returning to the cleaning page after navigation
     */
    function reinitializeCleaningButtons() {
        console.log("Reinitializing cleaning status buttons");

        // Get all cleaning status buttons
        const cleanStatusButtons =
            document.querySelectorAll(".clean-status-btn");

        // Get all cleaning notes inputs
        const cleaningNotes = document.querySelectorAll(".cleaning-notes");

        console.log(
            `Found ${cleanStatusButtons.length} buttons to reinitialize`
        );

        // Remove existing event listeners by cloning and replacing
        cleanStatusButtons.forEach((button) => {
            const newButton = button.cloneNode(true);
            button.parentNode.replaceChild(newButton, button);
        });

        // Re-attach click handlers to each cleaning status button
        document.querySelectorAll(".clean-status-btn").forEach((button) => {
            button.addEventListener("click", function () {
                const roomId = this.getAttribute("data-room");
                const status = this.getAttribute("data-status");
                const roomBox = this.closest(".room-box");
                const notesInput = roomBox.querySelector(".cleaning-notes");
                const notes = notesInput ? notesInput.value : "";

                console.log(
                    `Reinitialized button clicked: updating room ${roomId} to ${status}`
                );

                // Update visual status immediately
                updateButtonAppearance(roomBox, status);

                // Send AJAX request
                updateCleaningStatusAjax(roomId, status, notes);
            });
        });

        // Reinitialize note inputs by cloning and replacing
        cleaningNotes.forEach((input) => {
            const newInput = input.cloneNode(true);
            input.parentNode.replaceChild(newInput, input);
        });

        // Re-attach handlers to cleaning notes inputs
        document.querySelectorAll(".cleaning-notes").forEach((input) => {
            // Save original value
            input.dataset.originalValue = input.value;

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
            });
        });
    }

    /**
     * Update button appearance when clicked
     */
    function updateButtonAppearance(roomBox, status) {
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

    /**
     * Send AJAX request to update cleaning status
     */
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
                    window.storeCleaningStatusUpdate(roomId, status, notes);

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

    /**
     * Show a notification message
     */
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

    /**
     * Check if the current page needs a refresh based on navigation
     * This ensures that when users navigate back to the cleaning page,
     * it gets refreshed to properly reinitialize all button handlers
     */
    function checkIfPageNeedsRefresh() {
        // Only check on the cleaning page
        if (window.location.pathname.includes("/cleaning")) {
            // Create or check a session storage flag
            const lastPageVisit = sessionStorage.getItem("lastPageVisit");
            const currentTimestamp = Date.now();

            // Store the current path and timestamp
            const currentPath = window.location.pathname;

            if (lastPageVisit) {
                try {
                    const lastVisitData = JSON.parse(lastPageVisit);

                    // If we're returning to the cleaning page from another page
                    // and it's been more than 500ms since our last visit (to avoid refresh loops)
                    if (
                        lastVisitData.path !== currentPath &&
                        currentPath.includes("/cleaning") &&
                        currentTimestamp - lastVisitData.timestamp > 500
                    ) {
                        console.log(
                            "Detected navigation back to cleaning page, refreshing..."
                        );

                        // Store that we've refreshed this page
                        sessionStorage.setItem("cleaningPageRefreshed", "true");

                        // Reload the page once
                        window.location.reload();
                        return;
                    }
                } catch (e) {
                    console.error("Error parsing last page visit data", e);
                }
            }

            // Update the last page visit
            sessionStorage.setItem(
                "lastPageVisit",
                JSON.stringify({
                    path: currentPath,
                    timestamp: currentTimestamp,
                })
            );
        } else {
            // If we're on any other page, update the last visit info
            sessionStorage.setItem(
                "lastPageVisit",
                JSON.stringify({
                    path: window.location.pathname,
                    timestamp: Date.now(),
                })
            );
        }
    }
});
