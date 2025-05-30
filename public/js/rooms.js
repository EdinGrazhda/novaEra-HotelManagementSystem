// Room filtering and search functionality
document.addEventListener("DOMContentLoaded", function () {
    console.log("Room filtering functionality initializing...");

    // Hide cleaning status badges at the top of cards
    document
        .querySelectorAll(".room-box .absolute.top-0.right-0")
        .forEach((el) => {
            el.style.display = "none";
        });

    // Ensure room colors are set based only on room status, not cleaning status
    document.querySelectorAll(".room-box").forEach((roomBox) => {
        const roomStatus = roomBox.getAttribute("data-status");
        console.log(
            `Room ${
                roomBox.querySelector(".room-number").innerText
            } status: ${roomStatus}, cleaning: ${roomBox.getAttribute(
                "data-cleaning"
            )}`
        );

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

    // Get the filter buttons and search input
    const filterButtons = document.querySelectorAll(".filter-btn");
    const cleaningFilterButtons = document.querySelectorAll(
        ".cleaning-filter-btn"
    );
    const searchInput = document.getElementById("room-search");
    const roomBoxes = document.querySelectorAll(".room-box");
    const clearButton = document.getElementById("clear-search");

    // Log detected filter buttons for debugging
    console.log(`Found ${filterButtons.length} room status filter buttons`);
    console.log(
        `Found ${cleaningFilterButtons.length} room cleaning filter buttons`
    );

    // Current filter state
    let currentFilter = "all";
    let currentCleaningFilter = "all";

    // Function to filter rooms
    function filterRooms() {
        const filterValue = currentFilter;
        const cleaningFilterValue = currentCleaningFilter;
        const searchValue = searchInput
            ? searchInput.value.toLowerCase().trim()
            : "";

        console.log(
            `Filtering: Room Status = ${filterValue}, Cleaning Status = ${cleaningFilterValue}, Search = "${searchValue}"`
        );

        let visibleCount = 0;

        roomBoxes.forEach((box) => {
            const roomStatus = box.getAttribute("data-status");
            const roomCleaningStatus =
                box.getAttribute("data-cleaning") || "clean";
            const roomNumber = box
                .querySelector(".room-number")
                .innerText.toLowerCase();

            // Get room floor - handle case when the structure might vary
            let roomFloor = "";
            try {
                roomFloor = box
                    .querySelector(".room-info-item:nth-child(1) .font-medium")
                    .innerText.toLowerCase();
            } catch (e) {
                // Fallback to data-attribute if querySelector fails
                roomFloor = box.getAttribute("data-floor") || "";
            }

            // Get room category - handle case when the structure might vary
            let roomCategory = "";
            try {
                roomCategory = box
                    .querySelector(".room-info-item:nth-child(2) .font-medium")
                    .innerText.toLowerCase();
            } catch (e) {
                roomCategory = ""; // Fallback if not found
            }

            // Check if matches status filter
            const matchesStatusFilter =
                filterValue === "all" || roomStatus === filterValue;

            // Check if matches cleaning status filter
            const matchesCleaningFilter =
                cleaningFilterValue === "all" ||
                roomCleaningStatus === cleaningFilterValue;

            // Check if matches search term (room number, category or floor)
            const matchesSearch =
                searchValue === "" ||
                roomNumber.includes(searchValue) ||
                roomCategory.includes(searchValue) ||
                roomFloor.includes(searchValue);

            // Show or hide based on all filter conditions
            if (matchesStatusFilter && matchesCleaningFilter && matchesSearch) {
                box.style.display = "block";
                visibleCount++;
            } else {
                box.style.display = "none";
            }
        });

        console.log(
            `Filter results: ${visibleCount} rooms visible after filtering`
        );

        // Handle "no results" scenario inside the main content area
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
            const newFilter = this.getAttribute("data-filter");
            console.log(`Room status filter clicked: ${newFilter}`);

            // Remove active class from all buttons
            filterButtons.forEach((btn) =>
                btn.classList.remove("active-filter")
            );

            // Add active class to clicked button
            this.classList.add("active-filter");

            // Update current filter
            currentFilter = newFilter;

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
    // Add click event to each cleaning filter button
    cleaningFilterButtons.forEach((button) => {
        button.addEventListener("click", function () {
            const newCleaningFilter = this.getAttribute("data-cleaning-filter");
            console.log(`Cleaning filter clicked: ${newCleaningFilter}`);

            // Remove active class from all buttons
            cleaningFilterButtons.forEach((btn) =>
                btn.classList.remove("active-filter")
            );

            // Add active class to clicked button
            this.classList.add("active-filter");

            // Update current cleaning filter
            currentCleaningFilter = newCleaningFilter;

            // Apply filtering
            filterRooms();
        });
    });

    // Initial filtering when page loads
    filterRooms();

    // Log successful initialization
    console.log("Room filtering functionality initialized successfully");

    // Add CSS to better highlight the active filter buttons
    const style = document.createElement("style");
    style.textContent = `
        .filter-btn.active-filter,
        .cleaning-filter-btn.active-filter {
            font-weight: bold;
            box-shadow: 0 0 0 2px rgba(0, 0, 0, 0.2);
            transform: scale(1.05);
        }
    `;
    document.head.appendChild(style);

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
