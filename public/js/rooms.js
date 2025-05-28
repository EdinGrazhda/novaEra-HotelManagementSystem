// Room filtering and search functionality
document.addEventListener("DOMContentLoaded", function () {
    // Get the filter buttons and search input
    const filterButtons = document.querySelectorAll(".filter-btn");
    const searchInput = document.getElementById("room-search");
    const roomBoxes = document.querySelectorAll(".room-box");
    const clearButton = document.getElementById("clear-search");

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
            const roomCategory = box
                .querySelector(".room-info-item:nth-child(2) .font-medium")
                .innerText.toLowerCase();

            // Check if matches status filter
            const matchesStatusFilter =
                filterValue === "all" || roomStatus === filterValue;

            // Check if matches search term (room number, category or floor)
            const matchesSearch =
                searchValue === "" ||
                roomNumber.includes(searchValue) ||
                roomCategory.includes(searchValue) ||
                roomFloor.includes(searchValue);

            // Show or hide based on both filter conditions
            if (matchesStatusFilter && matchesSearch) {
                box.style.display = "block";
                visibleCount++;
            } else {
                box.style.display = "none";
            }
        }); // Handle "no results" scenario inside the main content area
        // We'll just make sure any message is properly contained
        const noResultsMsg = document.getElementById("no-search-results");
        if (noResultsMsg) {
            // If we find an existing message, remove it
            noResultsMsg.remove();
        }

        // We could add a hidden empty state message here if needed in the future
        // But it would need to be inserted into the grid element so it stays within the layout
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
    });

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
    filterRooms();

    // Safety check to ensure no messages are created outside the main content
    // Find any potential stray messages that might have been created outside the container
    document.querySelectorAll("#no-search-results").forEach((element) => {
        const container = document.querySelector(".container");
        // If element is not a child of the container, remove it
        if (container && !container.contains(element)) {
            element.remove();
        }
    });
});
