document.addEventListener("DOMContentLoaded", function () {

    const searchInput = document.getElementById("historySearch");
    const sortSelect = document.getElementById("historySort");
    const grid = document.getElementById("historyGrid");
    const noResult = document.getElementById("noSearchResult");

    if (!grid) return;

    function getCards() {
        return Array.from(grid.querySelectorAll(".history-card"));
    }

    function renderHistory() {

        const cards = getCards();

        // Search value
        const query = searchInput
            ? searchInput.value.trim().toLowerCase()
            : "";

        const sort = sortSelect
            ? sortSelect.value
            : "newest";

        let visibleCards = [];

        cards.forEach(function (card) {

            const month = (
                card.getAttribute("data-month") || ""
            ).toLowerCase();

            // Empty search = show everything
            const matched =
                query === "" || month.includes(query);

            if (matched) {
                card.style.display = "";
                visibleCards.push(card);
            } else {
                card.style.display = "none";
            }
        });

        // Sorting
        visibleCards.sort(function (a, b) {

            const aIndex =
                Number(a.getAttribute("data-index")) || 0;

            const bIndex =
                Number(b.getAttribute("data-index")) || 0;

            const aExpense =
                Number(a.getAttribute("data-expense")) || 0;

            const bExpense =
                Number(b.getAttribute("data-expense")) || 0;

            const aMeal =
                Number(a.getAttribute("data-meal")) || 0;

            const bMeal =
                Number(b.getAttribute("data-meal")) || 0;


            if (sort === "oldest") {
                return bIndex - aIndex;
            }

            if (sort === "expense-high") {
                return bExpense - aExpense;
            }

            if (sort === "meal-high") {
                return bMeal - aMeal;
            }

            // newest
            return aIndex - bIndex;
        });

        // Re-append sorted cards
        visibleCards.forEach(function (card) {
            grid.appendChild(card);
        });

        // Show "No matching history" ONLY when
        // there are records but search found nothing
        if (noResult) {
            noResult.hidden = visibleCards.length > 0;
        }
    }


    // Search
    if (searchInput) {
        searchInput.addEventListener("input", renderHistory);
    }


    // Sort
    if (sortSelect) {
        sortSelect.addEventListener("change", renderHistory);
    }


    // Modal
    window.openHistoryModal = function (button) {

        const card = button.closest(".history-card");

        if (!card) return;

        const data = card.querySelector(".history-data");

        const modal = document.getElementById("historyModal");

        if (!data || !modal) return;

        document.getElementById("modalMonth").textContent =
            data.dataset.month || "Month";

        document.getElementById("modalMember").textContent =
            data.dataset.member || "0";

        document.getElementById("modalMeal").textContent =
            data.dataset.meal || "0";

        document.getElementById("modalRate").textContent =
            (data.dataset.currency || "BDT") +
            " " +
            (data.dataset.rate || "0");

        document.getElementById("modalExpense").textContent =
            (data.dataset.currency || "BDT") +
            " " +
            (data.dataset.expense || "0");

        document.getElementById("modalFund").textContent =
            (data.dataset.currency || "BDT") +
            " " +
            (data.dataset.fund || "0");

        document.getElementById("modalDue").textContent =
            (data.dataset.currency || "BDT") +
            " " +
            (data.dataset.due || "0");

        modal.classList.add("active");
        modal.setAttribute("aria-hidden", "false");

        document.body.style.overflow = "hidden";
    };


    window.closeHistoryModal = function () {

        const modal = document.getElementById("historyModal");

        if (!modal) return;

        modal.classList.remove("active");
        modal.setAttribute("aria-hidden", "true");

        document.body.style.overflow = "";
    };


    // ESC close
    document.addEventListener("keydown", function (event) {

        if (event.key === "Escape") {
            window.closeHistoryModal();
        }

    });


    // IMPORTANT:
    // Initial load → show ALL history
    if (searchInput) {
        searchInput.value = "";
    }

    renderHistory();

});