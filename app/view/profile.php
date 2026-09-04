<link rel="stylesheet" href="/app/assets/css/home.css">
<link rel="stylesheet" href="/app/assets/css/profile.css?v=<?php echo time(); ?>">

<div id="home_page">
    <div class="nav"><?php include __DIR__ . "/layout/navbar.php"; ?></div>
    <div class="body_section">
        <div class="sidebar_section"><?php include __DIR__ . "/layout/sidebar.php"; ?></div>
        <div class="content_section">
            <div id="content_body">

                <div class="profile-card">

                    <?php if(!empty($errorMessage)): ?>
                        <p class="error-text"><?php echo htmlspecialchars($errorMessage); ?></p>
                    <?php endif; ?>

                    <?php if(!empty($msg)): ?>
                        <p class="success-text"><?php echo htmlspecialchars($msg); ?></p>
                    <?php endif; ?>

                    <form id="profileForm" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="update_profile_submit" value="1">

                        <div class="avatar-wrapper">
                            <div class="avatar-circle" id="avatarPreviewCircle">
                                <?php if(!empty($userRow["Avater"])): ?>
                                    <img id="avatarPreviewImg" src="<?php echo htmlspecialchars($userRow["Avater"]); ?>" alt="avatar" width="80" height="80" style="object-fit:cover; border-radius:50%;">
                                <?php else: ?>
                                    <svg id="avatarPreviewSvg" viewBox="0 0 80 80" width="80" height="80" xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="40" cy="40" r="40" fill="#f87171"/>
                                        <circle cx="40" cy="34" r="14" fill="#fde047"/>
                                        <path d="M 28 30 Q 40 14 52 30 Q 46 22 34 24 Z" fill="#1e293b"/>
                                        <polygon points="34,48 46,48 44,78 36,78" fill="#ffffff"/>
                                        <polygon points="38,48 42,48 43,68 37,68" fill="#ef4444"/>
                                        <path d="M 16 72 C 16 54, 26 48, 34 48 L 46 48 C 54 48, 64 54, 64 72 Z" fill="#f8fafc"/>
                                    </svg>
                                <?php endif; ?>
                            </div>
                            <button type="button" class="avatar-edit-badge" id="avatarEditBadge" onclick="triggerAvatarUpload()" title="Change Profile Picture" style="display:none;">
                                <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="#6b7280" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                                </svg>
                            </button>
                            <input type="file" id="avatarFileInput" name="profile_avatar" style="display: none;" accept="image/*" onchange="previewAvatar(this)">
                        </div>

                        <h2 class="user-display-name" id="nameDisplay"><?php echo htmlspecialchars($userRow["Name"] ?? ""); ?></h2>
                        <input type="text" class="profile-name-input" id="nameInput" name="profile_name" value="<?php echo htmlspecialchars($userRow["Name"] ?? ""); ?>" style="display:none;" required>

                        <div class="edit-profile-btn" id="saveControls" style="display:none; gap:10px;">
                            <button type="submit" id="saveProfileBtn">Save Changes</button>
                            <button type="button" id="cancelEditBtn" onclick="cancelEditMode()">Cancel</button>
                        </div>

                        <div class="profile-section">
                            <h4 class="section-heading">Account Info</h4>
                            <ul class="info-list">
                                <li class="info-item">
                                    <span class="item-icon icon-coral">@</span>
                                    <span class="item-text">Email: <?php echo htmlspecialchars($userRow["Email"] ?? ""); ?></span>
                                </li>
                                <li class="info-item">
                                    <span class="item-icon icon-coral">
                                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                        </svg>
                                    </span>
                                    <span class="item-text" id="phoneDisplay">Phone: <?php echo !empty($userRow["Phone"]) ? htmlspecialchars($userRow["Phone"]) : "Not Set Yet"; ?></span>
                                    <input type="text" class="profile-phone-input" id="phoneInput" name="profile_phone" value="<?php echo htmlspecialchars($userRow["Phone"] ?? ""); ?>" placeholder="01XXXXXXXXX" style="display:none;">
                                </li>
                            </ul>
                        </div>
                    </form>

                    <!-- Account Settings -->
                    <div class="profile-section">
                        <h4 class="section-heading">Account Settings</h4>
                        <ul class="info-list">
                            <li class="info-item">
                                <button type="button" class="info-link-btn" onclick="enterEditMode()">
                                    <span class="item-icon icon-coral">
                                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="12" cy="7" r="4"></circle>
                                        </svg>
                                    </span>
                                    <span class="item-text">Change Information</span>
                                </button>
                            </li>
                            <li class="info-item">
                                <button type="button" class="info-link-btn" onclick="openModal('editEmailModal')">
                                    <span class="item-icon icon-coral">@</span>
                                    <span class="item-text">Change Email</span>
                                </button>
                            </li>
                            <li class="info-item">
                                <button type="button" class="info-link-btn" onclick="openModal('editPasswordModal')">
                                    <span class="item-icon icon-coral">
                                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                        </svg>
                                    </span>
                                    <span class="item-text">Change Password</span>
                                </button>
                            </li>
                        </ul>
                    </div>

                    <a href="/app/controller/logout.php">
                        <button type="button" class="btn-logout">Logout</button>
                    </a>
                </div>

            </div>
            <div class="footer_section"><?php include __DIR__ . "/layout/footer.php"; ?></div>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- EDIT EMAIL MODAL: Current Email, New Email, Current Password   -->
<!-- ============================================================== -->
<div id="editEmailModal" class="modal-backdrop" style="display: none;">
    <div class="modal-card">
        <div class="modal-illustration">
            <svg width="130" height="90" viewBox="0 0 160 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="30" cy="40" r="6" fill="#384152"/>
                <circle cx="45" cy="20" r="7" fill="#f87171"/>
                <circle cx="95" cy="20" r="6" fill="#64748b"/>
                <circle cx="125" cy="30" r="8" fill="#f87171"/>
                <circle cx="110" cy="55" r="7" fill="#f87171"/>
                <polygon points="65,10 75,30 95,25 90,45 105,60 85,65 80,85 65,70 45,75 55,55 40,40 60,35" fill="#f8fafc" stroke="#cbd5e1" stroke-width="1.5" stroke-dasharray="3 3"/>
                <rect x="76" y="70" width="8" height="15" fill="#ca8a04"/>
                <path d="M80 60 C 70 65, 75 75, 80 75 C 85 75, 90 65, 80 60 Z" fill="#166534"/>
                <circle cx="48" cy="45" r="7" fill="#fcd34d"/>
                <path d="M42 55 L56 55 L52 75 L45 75 Z" fill="#f87171"/>
                <path d="M45 75 L40 92 L47 92 L50 80 L56 92 L62 92 Z" fill="#1e293b"/>
                <circle cx="115" cy="43" r="7" fill="#fcd34d"/>
                <path d="M109 52 L121 52 L120 70 L110 70 Z" fill="#f87171"/>
                <rect x="110" y="70" width="10" height="22" fill="#1e293b"/>
            </svg>
        </div>

        <h3 class="modal-title">Edit email</h3>
        <p class="modal-subtitle">Set updated Value</p>

        <form method="POST">
            <input type="hidden" name="update_email_submit" value="1">

            <div class="modal-form-group">
                <label class="modal-label">Current Email</label>
                <input type="email" class="modal-input input-disabled" value="<?php echo htmlspecialchars($userRow['Email'] ?? ''); ?>" readonly tabindex="-1">
            </div>

            <div class="modal-form-group">
                <label class="modal-label">New Email</label>
                <input type="email" name="new_email" class="modal-input" placeholder="Enter new email" required>
            </div>

            <div class="modal-form-group">
                <label class="modal-label">Current Password</label>
                <input type="password" name="old_password_email" class="modal-input" placeholder="Current Password" required>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-modal-cancel" onclick="closeModal('editEmailModal')">Cancel</button>
                <button type="submit" class="btn-modal-update">Update</button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================================== -->
<!-- EDIT PASSWORD MODAL: Current Password, New Password           -->
<!-- ============================================================== -->
<div id="editPasswordModal" class="modal-backdrop" style="display: none;">
    <div class="modal-card">
        <div class="modal-illustration">
            <svg width="130" height="90" viewBox="0 0 160 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="30" cy="40" r="6" fill="#384152"/>
                <circle cx="45" cy="20" r="7" fill="#f87171"/>
                <circle cx="95" cy="20" r="6" fill="#64748b"/>
                <circle cx="125" cy="30" r="8" fill="#f87171"/>
                <circle cx="110" cy="55" r="7" fill="#f87171"/>
                <polygon points="65,10 75,30 95,25 90,45 105,60 85,65 80,85 65,70 45,75 55,55 40,40 60,35" fill="#f8fafc" stroke="#cbd5e1" stroke-width="1.5" stroke-dasharray="3 3"/>
                <rect x="76" y="70" width="8" height="15" fill="#ca8a04"/>
                <path d="M80 60 C 70 65, 75 75, 80 75 C 85 75, 90 65, 80 60 Z" fill="#166534"/>
                <circle cx="48" cy="45" r="7" fill="#fcd34d"/>
                <path d="M42 55 L56 55 L52 75 L45 75 Z" fill="#f87171"/>
                <path d="M45 75 L40 92 L47 92 L50 80 L56 92 L62 92 Z" fill="#1e293b"/>
                <circle cx="115" cy="43" r="7" fill="#fcd34d"/>
                <path d="M109 52 L121 52 L120 70 L110 70 Z" fill="#f87171"/>
                <rect x="110" y="70" width="10" height="22" fill="#1e293b"/>
            </svg>
        </div>

        <h3 class="modal-title">Edit password</h3>
        <p class="modal-subtitle">Set updated Value</p>

        <form method="POST">
            <input type="hidden" name="update_password_submit" value="1">

            <div class="modal-form-group">
                <label class="modal-label">Current Password</label>
                <input type="password" name="old_password" class="modal-input" placeholder="Current Password" required>
            </div>

            <div class="modal-form-group">
                <label class="modal-label">New Password</label>
                <input type="password" name="new_password" class="modal-input" placeholder="New Password" required>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-modal-cancel" onclick="closeModal('editPasswordModal')">Cancel</button>
                <button type="submit" class="btn-modal-update">Update</button>
            </div>
        </form>
    </div>
</div>

<script src="/app/assets/js/profile.js?v=<?php echo time(); ?>"></script>