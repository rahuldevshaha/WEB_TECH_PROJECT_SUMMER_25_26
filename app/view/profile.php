
<link rel="stylesheet" href="/app/assets/css/home.css">
<link rel="stylesheet" href="/app/assets/css/profile.css">

    <div id="home_page">
        <div class="nav"><?php include "layout/navbar.php"; ?></div>
        <div class="body_section">
            <div class="sidebar_section"> <?php include "layout/sidebar.php";?></div>
            <div class="content_section">
                <div id="content_body">


                    <div class="profile-card">

                    <?php if(!empty($errorMessage)): ?>
                        <p class="error-text"><?php echo $errorMessage; ?></p>
                    <?php endif; ?>

                    <?php if(!empty($msg)): ?>
                        <p class="success-text"><?php echo $msg; ?></p>
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

                        
                        <h2 class="user-display-name" id="nameDisplay"><?php echo htmlspecialchars($userRow["Name"]); ?></h2>
                        <input type="text" class="profile-name-input" id="nameInput" name="profile_name" value="<?php echo htmlspecialchars($userRow["Name"]); ?>" style="display:none;" required>

                        
                        <div class="edit-profile-btn" id="editControls">
                            <button type="button" id="editProfileBtn" onclick="enterEditMode()">Edit Profile</button>
                        </div>
                        <div class="edit-profile-btn" id="saveControls" style="display:none; gap:10px;">
                            <button type="submit" id="saveProfileBtn">Save Changes</button>
                            <button type="button" id="cancelEditBtn" onclick="cancelEditMode()">Cancel</button>
                        </div>

                        
                        <div class="profile-section">
                            <h4 class="section-heading">Account Info</h4>
                            <ul class="info-list">
                                <li class="info-item">
                                    <span class="item-icon icon-coral">@</span>
                                    <span class="item-text">Email: <?php echo htmlspecialchars($userRow["Email"]); ?></span>
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


                    
                    <div class="profile-section">
                        <h4 class="section-heading">Account Settings</h4>
                        <ul class="info-list">
                            <li class="info-item">
                                <a href="/app/view/ComingSoon.php" class="info-link">
                                    <span class="item-icon icon-coral">@</span>
                                    <span class="item-text">Change Email</span>
                                </a>
                            </li>
                            <li class="info-item">
                                <a href="/app/view/ComingSoon.php" class="info-link">
                                    <span class="item-icon icon-coral">
                                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                        </svg>
                                    </span>
                                    <span class="item-text">Change Password</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    
                    <a href="/app/controller/logout.php" >
                        <button type="submit" class="btn-logout">Logout</button>
                    </a>
                </div>


                </div>
                <div class="footer_section"><?php include "layout/footer.php";?></div>
            </div>
        </div>
    </div>


    <script src="/app/assets/js/profile.js"></script>