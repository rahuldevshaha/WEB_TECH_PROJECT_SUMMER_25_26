function triggerAvatarUpload() {
    var fileInput = document.getElementById('avatarFileInput');
    if (fileInput) {
        fileInput.click();
    }
}

var originalNameValue = '';
var originalPhoneValue = '';
var originalAvatarHTML = '';

function enterEditMode() {
    var avatarCircle = document.getElementById('avatarPreviewCircle');
    if (avatarCircle) originalAvatarHTML = avatarCircle.innerHTML;

    var nameDisplay = document.getElementById('nameDisplay');
    var nameInput = document.getElementById('nameInput');
    var phoneDisplay = document.getElementById('phoneDisplay');
    var phoneInput = document.getElementById('phoneInput');
    var avatarBadge = document.getElementById('avatarEditBadge');
    var saveControls = document.getElementById('saveControls');

    if (nameInput) originalNameValue = nameInput.value;
    if (phoneInput) originalPhoneValue = phoneInput.value;

    if (nameDisplay) nameDisplay.style.display = 'none';
    if (nameInput) nameInput.style.display = 'block';

    if (phoneDisplay) phoneDisplay.style.display = 'none';
    if (phoneInput) phoneInput.style.display = 'inline-block';

    if (avatarBadge) avatarBadge.style.display = 'flex';
    if (saveControls) saveControls.style.display = 'flex';

    nameInput.focus();
}

function cancelEditMode() {
    var nameDisplay = document.getElementById('nameDisplay');
    var nameInput = document.getElementById('nameInput');
    var phoneDisplay = document.getElementById('phoneDisplay');
    var phoneInput = document.getElementById('phoneInput');
    var avatarBadge = document.getElementById('avatarEditBadge');
    var saveControls = document.getElementById('saveControls');

    if (nameInput) nameInput.value = originalNameValue;
    if (phoneInput) phoneInput.value = originalPhoneValue;

    var avatarCircle = document.getElementById('avatarPreviewCircle');
    var fileInput = document.getElementById('avatarFileInput');
    if (avatarCircle && originalAvatarHTML) avatarCircle.innerHTML = originalAvatarHTML;
    if (fileInput) fileInput.value = '';

    if (nameDisplay) nameDisplay.style.display = 'block';
    if (nameInput) nameInput.style.display = 'none';

    if (phoneDisplay) phoneDisplay.style.display = 'inline';
    if (phoneInput) phoneInput.style.display = 'none';

    if (avatarBadge) avatarBadge.style.display = 'none';
    if (saveControls) saveControls.style.display = 'none';
}

function previewAvatar(input) {
    if (!input.files || !input.files[0]) return;

    var reader = new FileReader();
    reader.onload = function (e) {
        var circle = document.getElementById('avatarPreviewCircle');
        if (circle) {
            circle.innerHTML = '<img src="' + e.target.result + '" alt="avatar" width="80" height="80" style="object-fit:cover; border-radius:50%;">';
        }
    };
    reader.readAsDataURL(input.files[0]);
}

function openModal(modalId) {
    var modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'flex';
        modal.classList.add('active');
    }
}

function closeModal(modalId) {
    var modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'none';
        modal.classList.remove('active');
    }
}

window.addEventListener('click', function(e) {
    if (e.target.classList && e.target.classList.contains('modal-backdrop')) {
        e.target.style.display = 'none';
        e.target.classList.remove('active');
    }
});