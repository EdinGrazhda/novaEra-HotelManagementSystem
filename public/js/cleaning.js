// Cleaning room management functionality
document.addEventListener("DOMContentLoaded", function () {
    // Get the filter buttons and search input
    const filterButtons = document.querySelectorAll(".filter-btn");
    const searchInput = document.getElementById("room-search");
    const roomBoxes = document.querySelectorAll(".room-box");
    const clearButton = document.getElementById("clear-search");
    const cleanStatusButtons = document.querySelectorAll(".clean-status-btn");
    const cleaningNotes = document.querySelectorAll(".cleaning-notes");

    // Current filter state
    let currentFilter = "all";

    // Function to filter rooms
    function filterRooms() {
        const filterValue = currentFilter;
        const searchValue = searchInput
            ? searchInput.value.toLowerCase().trim()
            : "";
        let visibleCount = 0;

        roomBoxes.forEach((box) => {
            const roomStatus = box.getAttribute("data-status");
            const roomNumber = box
                .querySelector(".room-number")
                .innerText.toLowerCase();
            const roomFloor = box
                .querySelector(".room-info-item:nth-child(1) .font-medium")
                .innerText.toLowerCase();

            // Check if matches status filter
            const matchesStatusFilter =
                filterValue === "all" || roomStatus === filterValue;

            // Check if matches search term
            const matchesSearch =
                searchValue === "" ||
                roomNumber.includes(searchValue) ||
                roomFloor.includes(searchValue);

            // Show or hide based on both filter conditions
            if (matchesStatusFilter && matchesSearch) {
                box.style.display = "block";
                visibleCount++;
            } else {
                box.style.display = "none";
            }
        });

        // Remove any existing "no results" message if it exists
        const noResultsMsg = document.getElementById("no-search-results");
        if (noResultsMsg) {
            noResultsMsg.remove();
        }
    }

    // Add click event to each filter button
    filterButtons.forEach((button) => {
        button.addEventListener("click", function () {
            // Remove active class from all buttons
            filterButtons.forEach((btn) =>
                btn.classList.remove("active-filter")
            );

            // Add active class to clicked button
            this.classList.add("active-filter");

            // Update current filter
            currentFilter = this.getAttribute("data-filter");

            // Apply filtering
            filterRooms();
        });
    }); // Add click event to each cleaning status button
    cleanStatusButtons.forEach((button) => {
        button.addEventListener("click", function () {
            const roomId = this.getAttribute("data-room");
            const status = this.getAttribute("data-status");
            const roomBox = this.closest(".room-box");
            const notesInput = roomBox.querySelector(".cleaning-notes");
            const notes = notesInput ? notesInput.value : "";

            console.log(
                `Updating room ${roomId} status to ${status} with notes: ${notes}`
            );

            // Store the button for later reference
            const clickedButton = this;

            // Show visual feedback immediately
            updateRoomBoxAppearance(roomBox, status);

            // Update buttons appearance
            const statusButtons = roomBox.querySelectorAll(".clean-status-btn");
            statusButtons.forEach((btn) => {
                btn.classList.remove(
                    "border-2",
                    "border-green-500",
                    "border-red-500",
                    "border-yellow-500"
                );
            });
            this.classList.add(
                "border-2",
                status === "clean"
                    ? "border-green-500"
                    : status === "not_cleaned"
                    ? "border-red-500"
                    : "border-yellow-500"
            );

            // Send AJAX request to update status
            updateCleaningStatus(roomId, status, notes);
        });
    }); // Add input events to cleaning notes inputs
    cleaningNotes.forEach((input) => {
        // Save on blur (when user clicks outside)
        input.addEventListener("blur", function () {
            const roomId = this.getAttribute("data-room");
            const roomBox = this.closest(".room-box");
            const status = roomBox.getAttribute("data-status");
            const notes = this.value;

            // Send AJAX request to update notes
            updateCleaningStatus(roomId, status, notes);
        });

        // Also save on Enter key press
        input.addEventListener("keypress", function (event) {
            if (event.key === "Enter") {
                event.preventDefault(); // Prevent form submission
                const roomId = this.getAttribute("data-room");
                const roomBox = this.closest(".room-box");
                const status = roomBox.getAttribute("data-status");
                const notes = this.value;

                // Remove focus to trigger blur event
                this.blur();

                // Send AJAX request to update notes
                updateCleaningStatus(roomId, status, notes);
            }
        });
    });

    // Function to update room box appearance based on cleaning status
    function updateRoomBoxAppearance(roomBox, status) {
        // Update the data-status attribute
        roomBox.setAttribute("data-status", status);

        // Update the border and background color
        if (status === "clean") {
            roomBox.style.borderColor = "#10B981";
            roomBox.style.backgroundColor = "#ECFDF5";
        } else if (status === "not_cleaned") {
            roomBox.style.borderColor = "#EF4444";
            roomBox.style.backgroundColor = "#FEF2F2";
        } else {
            // in_progress
            roomBox.style.borderColor = "#F59E0B";
            roomBox.style.backgroundColor = "#FEF3C7";
        }

        // Update the status badge text and color
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
    } // Function to send AJAX request to update cleaning status
    function updateCleaningStatus(roomId, status, notes) {
        // Find the room box to add spinner
        const roomBox =
            document.querySelector(`.room-box[data-room="${roomId}"]`) ||
            document
                .querySelector(
                    `.room-box .clean-status-btn[data-room="${roomId}"]`
                )
                ?.closest(".room-box");

        // Create and show spinner
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

        console.log(`Sending request for room ${roomId} with status ${status}`);

        // Create a form data object
        const formData = new FormData();
        formData.append("cleaning_status", status);
        formData.append("cleaning_notes", notes);
        formData.append("_method", "PATCH");
        formData.append("_token", csrfToken);

        // Make the AJAX request
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

                    // Update the room box with the returned data
                    const updatedRoomBox = document.querySelector(
                        `.room-box[data-room="${roomId}"]`
                    );
                    if (updatedRoomBox) {
                        // Update the status attribute
                        updatedRoomBox.setAttribute(
                            "data-status",
                            data.cleaning_status
                        );

                        // Update the note input if it exists and has changed
                        const noteInput =
                            updatedRoomBox.querySelector(".cleaning-notes");
                        if (
                            noteInput &&
                            noteInput.value !== data.cleaning_notes
                        ) {
                            noteInput.value = data.cleaning_notes || "";
                        }

                        // Update the status badge
                        const statusBadge =
                            updatedRoomBox.querySelector(".room-status-badge");
                        if (statusBadge) {
                            const displayStatus =
                                data.cleaning_status === "not_cleaned"
                                    ? "Not Cleaned"
                                    : data.cleaning_status === "in_progress"
                                    ? "In Progress"
                                    : "Clean";
                            statusBadge.textContent = displayStatus;
                        }
                    }

                    // Show a small notification
                    showNotification("Status updated successfully", "success");
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

    // Add input event to search box for real-time searching
    if (searchInput) {
        // Show/hide clear button based on search input
        searchInput.addEventListener("input", function () {
            if (this.value.length > 0 && clearButton) {
                clearButton.style.display = "flex";
            } else if (clearButton) {
                clearButton.style.display = "none";
            }
            filterRooms();
        });
    }

    // Clear search when clear button is clicked
    if (clearButton) {
        clearButton.addEventListener("click", function () {
            if (searchInput) {
                searchInput.value = "";
                this.style.display = "none";
                filterRooms();
                searchInput.focus();
            }
        });
    }

    // Initial filtering when page loads
    filterRooms(); // Safety check to ensure no messages are created outside the main content
    document.querySelectorAll("#no-search-results").forEach((element) => {
        const container = document.querySelector(".container");
        // If element is not a child of the container, remove it
        if (container && !container.contains(element)) {
            element.remove();
        }
    });

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
