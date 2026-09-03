function switchMemberTab(tab) {
    var memberListSec = document.getElementById('memberListSection');
    var addMemberSec = document.getElementById('addMemberSection');
    var memberListBtn = document.getElementById('tabMemberListBtn');
    var addMemberBtn = document.getElementById('tabAddMemberBtn');

    if (tab === 'memberList') {
        if (memberListSec) memberListSec.classList.add('active');
        if (addMemberSec) addMemberSec.classList.remove('active');
        if (memberListBtn) memberListBtn.classList.add('active');
        if (addMemberBtn) addMemberBtn.classList.remove('active');
    } else {
        if (addMemberSec) addMemberSec.classList.add('active');
        if (memberListSec) memberListSec.classList.remove('active');
        if (addMemberBtn) addMemberBtn.classList.add('active');
        if (memberListBtn) memberListBtn.classList.remove('active');
    }
}

var memberSearchTimer = null;
var memberSearchXhrController = null;

function onMemberSearchInput(term) {
    var clearBtn = document.getElementById('memberSearchClearBtn');
    if (clearBtn) clearBtn.classList.toggle('visible', term.length > 0);

    clearTimeout(memberSearchTimer);

    memberSearchTimer = setTimeout(function() {
        runMemberSearch(term);
    }, 300);
}

function clearMemberSearch() {
    var input = document.getElementById('memberSearch');
    if (input) input.value = '';

    var clearBtn = document.getElementById('memberSearchClearBtn');
    if (clearBtn) clearBtn.classList.remove('visible');

    clearTimeout(memberSearchTimer);
    runMemberSearch('');
}

function runMemberSearch(term) {
    var spinner = document.getElementById('memberSearchSpinner');
    if (spinner) spinner.classList.add('active');

    if (memberSearchXhrController) {
        memberSearchXhrController.abort();
    }

    memberSearchXhrController = new XMLHttpRequest();

    memberSearchXhrController.open(
        'GET',
        '/app/controller/components/messMember.php?search_members=' + encodeURIComponent(term),
        true
    );

    memberSearchXhrController.onreadystatechange = function() {
        if (memberSearchXhrController.readyState === 4) {
            if (memberSearchXhrController.status >= 200 && memberSearchXhrController.status < 300) {
                try {
                    var data = JSON.parse(memberSearchXhrController.responseText);
                    renderMemberCards(data.members || [], !!data.isManager);
                } catch (err) {
                    console.error('Member search failed', err);
                }
            } else if (memberSearchXhrController.status !== 0) {
                console.error('Member search failed');
            }

            if (spinner) spinner.classList.remove('active');
        }
    };

    memberSearchXhrController.onerror = function() {
        console.error('Member search failed');
        if (spinner) spinner.classList.remove('active');
    };

    memberSearchXhrController.send();
}

function svgEl(markup) {
    var wrapper = document.createElement('div');
    wrapper.innerHTML = markup.trim();
    return wrapper.firstChild;
}

function buildMemberCardElement(m, isManager) {
    var bazarCount = Array.isArray(m.bazarDates) ? m.bazarDates.length : 0;

    var card = document.createElement('div');
    card.className = 'member-card';

    var header = document.createElement('div');
    header.className = 'member-card-header';

    var userMeta = document.createElement('div');
    userMeta.className = 'user-meta';
    userMeta.appendChild(svgEl(
        '<div class="avatar-circle"><svg viewBox="0 0 38 38" width="38" height="38">' +
        '<circle cx="19" cy="19" r="19" fill="#F87171"/>' +
        '<circle cx="19" cy="15" r="7" fill="#FED7AA"/>' +
        '<path d="M7 34c0-6 5-9 12-9s12 3 12 9" fill="#FEE2E2"/></svg></div>'
    ));

    var textInfo = document.createElement('div');
    textInfo.className = 'user-text-info';

    var h4 = document.createElement('h4');
    h4.innerText = m.name;

    var roleBadge = document.createElement('span');
    roleBadge.className = 'role-badge';
    roleBadge.innerText = m.role;

    textInfo.appendChild(h4);
    textInfo.appendChild(roleBadge);
    userMeta.appendChild(textInfo);
    header.appendChild(userMeta);

    if (m.role != 'Manager') {
        var removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'remove-btn';
        removeBtn.innerText = 'Remove';
        removeBtn.onclick = function() {
            openDeleteModal(m.userId);
        };
        header.appendChild(removeBtn);
    }

    card.appendChild(header);

    var emailId = 'email_' + m.userId;
    var emailRow = document.createElement('div');
    emailRow.className = 'member-detail-line copy-email-row';
    emailRow.innerHTML =
        '<span class="email-left"><strong>Email:</strong> <span id="' + emailId + '"></span></span>' +
        '<button type="button" class="copy-btn" title="Copy Email">' +
        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' +
        '<rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>' +
        '<path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg></button>';

    emailRow.querySelector('#' + emailId).innerText = m.email;
    emailRow.querySelector('.copy-btn').onclick = function() {
        copyEmail(emailId);
    };

    card.appendChild(emailRow);

    var phoneRow = document.createElement('div');
    phoneRow.className = 'member-detail-line';
    phoneRow.innerHTML = '<strong>Phone:</strong> ';
    phoneRow.appendChild(document.createTextNode(m.phone ? m.phone : 'N/A'));
    card.appendChild(phoneRow);

    var bazarRow = document.createElement('div');
    bazarRow.className = 'row-action-section';

    var bazarLabel = document.createElement('span');
    bazarLabel.innerText = 'Bazar Dates';
    bazarRow.appendChild(bazarLabel);

    var actionIcons = document.createElement('div');
    actionIcons.className = 'action-icons';

    var tag = document.createElement('span');
    tag.className = 'no-data-tag';
    tag.innerText = bazarCount > 0
        ? (bazarCount + (bazarCount > 1 ? ' Dates Set' : ' Date Set'))
        : 'No Bazar Date Set';

    actionIcons.appendChild(tag);

    if (isManager) {
        var editBtn = svgEl(
            '<button type="button" class="icon-action-btn" title="Edit Bazar Dates">' +
            '<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="#2b2b2b" stroke-width="2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg></button>'
        );

        editBtn.onclick = function() {
            openCalendarModal(m.userId, m.bazarDates || []);
        };

        actionIcons.appendChild(editBtn);

        if (bazarCount > 0 && m.hasFutureDates) {
            var delBtn = svgEl(
                '<button type="button" class="icon-action-btn" title="Delete Upcoming Bazar Dates">' +
                '<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="#2b2b2b" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg></button>'
            );

            delBtn.onclick = function() {
                openBazarDeleteModal(m.userId);
            };

            actionIcons.appendChild(delBtn);
        }
    }

    bazarRow.appendChild(actionIcons);
    card.appendChild(bazarRow);

    return card;
}

function renderMemberCards(members, isManager) {
    var grid = document.getElementById('memberCardsGrid');
    var noResults = document.getElementById('memberSearchNoResults');

    if (!grid) return;

    grid.innerHTML = '';

    if (!members.length) {
        if (noResults) noResults.style.display = 'block';
        return;
    }

    if (noResults) noResults.style.display = 'none';

    var frag = document.createDocumentFragment();

    members.forEach(function(m) {
        frag.appendChild(buildMemberCardElement(m, isManager));
    });

    grid.appendChild(frag);
}

function autoGenerateEmail(name) {
    var emailInput = document.getElementById('newMemberEmail');
    if (!emailInput) return;

    var clean = name.toLowerCase().replace(/[^a-z0-9]/g, '');

    if (clean.length > 0) {
        emailInput.value =
            clean + Math.random().toString(36).substring(2, 6) + "@mm.app";
    } else {
        emailInput.value = "";
    }
}

function enableEmailTyping() {
    var emailInput = document.getElementById('newMemberEmail');

    if (emailInput) {
        emailInput.removeAttribute('readonly');
        emailInput.classList.add('editable-focus');
        emailInput.focus();
        emailInput.select();
    }
}

function copyEmail(id) {
    var emailElem = document.getElementById(id);
    if (!emailElem) return;

    var text = emailElem.innerText;

    navigator.clipboard.writeText(text).then(function() {
        alert("Email copied: " + text);
    }).catch(function(err) {
        console.error("Failed to copy", err);
    });
}

var calendarSelectedDates = new Set();
var calendarViewDate = new Date();
var calendarTargetUserId = null;

var monthNames = [
    "January", "February", "March", "April", "May", "June",
    "July", "August", "September", "October", "November", "December"
];

function pad2(n) {
    return n < 10 ? "0" + n : "" + n;
}

function formatDateStr(y, m, d) {
    return y + "-" + pad2(m + 1) + "-" + pad2(d);
}

function renderCalendarGrid() {
    var grid = document.getElementById('calendarGrid');
    var label = document.getElementById('calendarMonthLabel');

    if (!grid || !label) return;

    var oldCells = grid.querySelectorAll('.day-cell');

    oldCells.forEach(function(c) {
        c.remove();
    });

    var year = calendarViewDate.getFullYear();
    var month = calendarViewDate.getMonth();

    label.innerText = monthNames[month] + " " + year;

    var firstDay = new Date(year, month, 1).getDay();
    var daysInMonth = new Date(year, month + 1, 0).getDate();
    var prevMonthDays = new Date(year, month, 0).getDate();

    var assignments =
        (typeof allBazarAssignments !== 'undefined' && allBazarAssignments)
        ? allBazarAssignments
        : {};

    var today =
        (typeof todayDateStr !== 'undefined' && todayDateStr)
        ? todayDateStr
        : formatDateStr(
            new Date().getFullYear(),
            new Date().getMonth(),
            new Date().getDate()
        );

    var frag = document.createDocumentFragment();

    for (var i = firstDay - 1; i >= 0; i--) {
        var muted = document.createElement('span');
        muted.className = 'day-cell muted';
        muted.innerText = prevMonthDays - i;
        frag.appendChild(muted);
    }

    for (var d = 1; d <= daysInMonth; d++) {
        (function(d) {
            var dateStr = formatDateStr(year, month, d);

            var cell = document.createElement('span');
            cell.innerText = d;
            cell.setAttribute('data-date', dateStr);

            var assignment = assignments[dateStr];
            var isMine = calendarSelectedDates.has(dateStr);
            var isPast = dateStr < today;

            if (isPast) {
                if (isMine) {
                    cell.className = 'day-cell selected locked';
                    cell.title = 'Past assigned date (cannot be removed)';
                } else if (assignment) {
                    cell.className = 'day-cell taken';
                    cell.title = 'Assigned to ' + assignment.name;
                } else {
                    cell.className = 'day-cell muted-past';
                    cell.title = 'Past date';
                }
            } else if (assignment && assignment.userId !== calendarTargetUserId) {
                cell.className = 'day-cell taken';
                cell.title = 'Assigned to ' + assignment.name;
            } else {
                cell.className = 'day-cell' + (isMine ? ' selected' : '');

                cell.onclick = function() {
                    if (calendarSelectedDates.has(dateStr)) {
                        calendarSelectedDates.delete(dateStr);
                        cell.classList.remove('selected');
                    } else {
                        calendarSelectedDates.add(dateStr);
                        cell.classList.add('selected');
                    }
                };
            }

            frag.appendChild(cell);
        })(d);
    }

    var totalCells = firstDay + daysInMonth;

    var trailing =
        (totalCells % 7 === 0)
        ? 0
        : (7 - (totalCells % 7));

    for (var t = 1; t <= trailing; t++) {
        var muted2 = document.createElement('span');
        muted2.className = 'day-cell muted';
        muted2.innerText = t;
        frag.appendChild(muted2);
    }

    grid.appendChild(frag);
}

function shiftCalendarMonth(delta) {
    calendarViewDate.setMonth(calendarViewDate.getMonth() + delta);
    renderCalendarGrid();
}

function openCalendarModal(userId, existingDates) {
    calendarTargetUserId = userId || null;

    calendarSelectedDates =
        new Set(Array.isArray(existingDates) ? existingDates : []);

    calendarViewDate = new Date();

    var userIdInput = document.getElementById('calBazarUserId');

    if (userIdInput) {
        userIdInput.value = userId || '';
    }

    renderCalendarGrid();

    var modal = document.getElementById('calendarModal');

    if (modal) {
        modal.classList.add('active');
    }
}

function closeCalendarModal() {
    var modal = document.getElementById('calendarModal');

    if (modal) {
        modal.classList.remove('active');
    }
}

function prepareBazarSubmit() {
    var datesInput = document.getElementById('calBazarDatesInput');

    if (datesInput) {
        datesInput.value =
            JSON.stringify(Array.from(calendarSelectedDates));
    }

    var userIdInput = document.getElementById('calBazarUserId');

    if (!userIdInput || !userIdInput.value) {
        alert('No member selected.');
        return false;
    }

    return true;
}

function openBazarDeleteModal(uId) {
    var delInput = document.getElementById('delBazarUserId');

    if (delInput && uId) {
        delInput.value = uId;
    }

    var modal = document.getElementById('deleteBazarModal');

    if (modal) {
        modal.classList.add('active');
    }
}

function closeBazarDeleteModal() {
    var modal = document.getElementById('deleteBazarModal');

    if (modal) {
        modal.classList.remove('active');
    }
}

function openPermissionModal(uId) {
    var idInput = document.getElementById('permUserId');

    if (idInput && uId) {
        idInput.value = uId;
    }

    var modal = document.getElementById('permissionModal');

    if (modal) {
        modal.classList.add('active');
    }
}

function closePermissionModal() {
    var modal = document.getElementById('permissionModal');

    if (modal) {
        modal.classList.remove('active');
    }
}

function savePermissionAndShowPopup() {
    var permSelect = document.getElementById('permSelect');
    var permLabel = document.getElementById('permLabel');

    if (permSelect && permSelect.value) {
        if (permLabel) {
            permLabel.innerText = permSelect.value;
        }

        closePermissionModal();

        var successModal =
            document.getElementById('permSuccessModal');

        if (successModal) {
            successModal.classList.add('active');
        }
    } else {
        alert('অনুগ্রহ করে একটি পারমিশন সিলেক্ট করুন।');
    }
}

function closePermSuccessModal() {
    var modal = document.getElementById('permSuccessModal');

    if (modal) {
        modal.classList.remove('active');
    }
}

function openDeleteModal(uId) {
    var delInput = document.getElementById('delUserId');

    if (delInput && uId) {
        delInput.value = uId;
    }

    var modal = document.getElementById('deleteConfirmModal');

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

var memberEmailCheckTimer = null;

function onMemberEmailInput(email) {
    var nameInput = document.getElementById('newMemberName');

    if (!nameInput) return;

    clearTimeout(memberEmailCheckTimer);

    var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!emailPattern.test(email)) {
        nameInput.removeAttribute('readonly');
        return;
    }

    memberEmailCheckTimer = setTimeout(function() {
        var xhr = new XMLHttpRequest();

        xhr.open(
            'GET',
            '/app/controller/components/messMember.php?check_email=' +
            encodeURIComponent(email),
            true
        );

        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                if (xhr.status >= 200 && xhr.status < 300) {
                    try {
                        var data = JSON.parse(xhr.responseText);

                        if (data.found) {
                            nameInput.value = data.name;
                            nameInput.setAttribute('readonly', 'readonly');
                        } else {
                            nameInput.value = '';
                            nameInput.removeAttribute('readonly');
                        }
                    } catch (err) {
                        console.error('Email check failed', err);
                    }
                } else {
                    console.error('Email check failed');
                }
            }
        };

        xhr.onerror = function() {
            console.error('Email check failed');
        };

        xhr.send();
    }, 400);
}