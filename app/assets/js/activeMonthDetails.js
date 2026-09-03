
        function showTab(tabId, btn) {
    var panes = document.querySelectorAll('.tab-pane');
    for (var i = 0; i < panes.length; i++) {
        panes[i].classList.remove('active');
    }

    var buttons = document.querySelectorAll('.tab-btn');
    for (var j = 0; j < buttons.length; j++) {
        buttons[j].classList.remove('active');
    }

    var targetPane = document.getElementById(tabId);
    if (targetPane) {
        targetPane.classList.add('active');
    }
    if (btn) {
        btn.classList.add('active');
    }
}

function openUpdateModal(amount, note) {
    var amountField = document.getElementById('editAmount');
    var detailsField = document.getElementById('editDetails');
    var modal = document.getElementById('updateCostModal');

    if (amountField) {
        amountField.value = amount;
    }
    if (detailsField) {
        detailsField.value = note;
    }
    if (modal) {
        modal.classList.add('active');
    }
}

function closeUpdateModal() {
    var modal = document.getElementById('updateCostModal');
    if (modal) {
        modal.classList.remove('active');
    }
}

function openDeleteModal(id) {
    var delIdInput = document.getElementById('delRecordId');
    var modal = document.getElementById('deleteConfirmModal');

    if (delIdInput) {
        delIdInput.value = id;
    }
    if (modal) {
        modal.classList.add('active');
    }
}

function closeDeleteModal() {
    var modal = document.getElementById('deleteConfirmModal');
    if (modal) {
        modal.classList.remove('active');
    }
}
    