document.addEventListener("DOMContentLoaded", function () {

    window.showTab = function (tabId, btn) {
        document.querySelectorAll(".tab-pane").forEach(function (pane) {
            pane.classList.remove("active");
        });

        document.querySelectorAll(".tab-btn").forEach(function (button) {
            button.classList.remove("active");
        });

        var targetPane = document.getElementById(tabId);
        if (targetPane) targetPane.classList.add("active");
        if (btn) btn.classList.add("active");

        // Save currently selected tab
        sessionStorage.setItem("activeMonthDetailsTab", tabId);
    };

    window.openMealEditModal = function (mealRecordId, mealDate, morning, lunch, dinner) {
        var modal = document.getElementById("mealEditModal");
        if (!modal) return;

        document.getElementById("editMealRecordId").value = mealRecordId;
        document.getElementById("editMealDate").value = mealDate;
        document.getElementById("editMealMorning").checked = Number(morning) === 1;
        document.getElementById("editMealLunch").checked = Number(lunch) === 1;
        document.getElementById("editMealDinner").checked = Number(dinner) === 1;

        modal.classList.add("active");
    };

    window.closeMealEditModal = function () {
        var modal = document.getElementById("mealEditModal");
        if (modal) modal.classList.remove("active");
    };

    window.openDepositEditModal = function (fundId, submittedBy, amount, note, submitDate) {
        var modal = document.getElementById("depositEditModal");
        if (!modal) return;

        document.getElementById("editFundId").value = fundId;
        document.getElementById("editDepositMember").value = submittedBy;
        document.getElementById("editDepositAmount").value = amount;
        document.getElementById("editDepositNote").value = note || "";
        document.getElementById("editDepositDate").value = submitDate;

        modal.classList.add("active");
    };

    window.closeDepositEditModal = function () {
        var modal = document.getElementById("depositEditModal");
        if (modal) modal.classList.remove("active");
    };

    window.filterOtherCosts = function (selectedType) {
        var items = document.querySelectorAll(".other-cost-item");
        var visibleCount = 0;

        items.forEach(function (item) {
            var itemType = item.getAttribute("data-cost-type");
            var show = selectedType === "all" || itemType === selectedType;

            item.style.display = show ? "flex" : "none";
            if (show) visibleCount++;
        });

        var emptyState = document.getElementById("otherCostFilterEmpty");
        if (emptyState) {
            emptyState.style.display = visibleCount === 0 ? "flex" : "none";
        }
    };

    window.openCostEditModal = function (expenseId, costType, costBy, amount, note, costDate) {
        var modal = document.getElementById("costEditModal");
        if (!modal) return;

        document.getElementById("editExpenseId").value = expenseId;
        document.getElementById("editCostType").value = costType;
        document.getElementById("editCostBy").value = costBy;
        document.getElementById("editCostAmount").value = amount;
        document.getElementById("editCostNote").value = note || "";
        document.getElementById("editCostDate").value = costDate;

        modal.classList.add("active");
    };

    window.closeCostEditModal = function () {
        var modal = document.getElementById("costEditModal");
        if (modal) modal.classList.remove("active");
    };

    window.openDeleteConfirm = function (type) {
        var confirmModal = document.getElementById("deleteConfirmModal");
        if (!confirmModal) return;

        confirmModal.dataset.deleteType = type;

        if (type === "deposit") {
            document.getElementById("deleteFundId").value =
                document.getElementById("editFundId").value;
        } else if (type === "cost") {
            document.getElementById("deleteExpenseId").value =
                document.getElementById("editExpenseId").value;
        }

        confirmModal.classList.add("active");
    };

    window.closeDeleteConfirm = function () {
        var modal = document.getElementById("deleteConfirmModal");
        if (modal) modal.classList.remove("active");
    };

    window.submitDelete = function () {
        var modal = document.getElementById("deleteConfirmModal");
        if (!modal) return;

        var type = modal.dataset.deleteType;

        if (type === "deposit") {
            document.getElementById("deleteDepositForm").submit();
        } else if (type === "cost") {
            document.getElementById("deleteCostForm").submit();
        }
    };

    document.querySelectorAll(".modal-overlay").forEach(function (modal) {
        modal.addEventListener("click", function (event) {
            if (event.target === modal) {
                modal.classList.remove("active");
            }
        });
    });

    document.addEventListener("keydown", function (event) {
        if (event.key === "Escape") {
            document.querySelectorAll(".modal-overlay.active").forEach(function (modal) {
                modal.classList.remove("active");
            });
        }
    });

    // Restore previously selected tab after page reload
    var savedTab = sessionStorage.getItem("activeMonthDetailsTab");

    if (savedTab) {
        var savedPane = document.getElementById(savedTab);
        var savedBtn = document.querySelector(
            '.tab-btn[onclick*="' + savedTab + '"]'
        );

        if (savedPane && savedBtn) {
            window.showTab(savedTab, savedBtn);
        }
    }
});