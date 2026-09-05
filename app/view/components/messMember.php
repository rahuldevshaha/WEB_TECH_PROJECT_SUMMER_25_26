<link rel="stylesheet" href="/app/view/assets/css/successPopups.css">


    <link rel="stylesheet" href="/app/view/assets/css/messMember.css">
    <link rel="stylesheet" href="/app/view/assets/css/home.css">


    
    <div id="home_page">
        <div class="nav"><?php include __DIR__ . "/../layout/navbar.php"; ?></div>
        <div class="body_section">
            <div class="sidebar_section"> <?php include __DIR__ . "/../layout/sidebar.php";?></div>
            <div class="content_section">
                <div id="content_body">



                    <div class="main-container">
                        <h1 class="page-title">Mess Members</h1>

                        
                        <div class="tab-box">
                            <button type="button" class="tab-btn<?php echo ($activeTab == 'memberList') ? ' active' : ''; ?>" id="tabMemberListBtn" onclick="switchMemberTab('memberList')">All Members List</button>
                            <button type="button" class="tab-btn<?php echo ($activeTab == 'addMember') ? ' active' : ''; ?>" id="tabAddMemberBtn" onclick="switchMemberTab('addMember')">Add New Member</button>
                        </div>

                        
                        <div id="memberListSection" class="tab-content<?php echo ($activeTab == 'memberList') ? ' active' : ''; ?>">
                            <p class="notice-text">Your mess has <strong><?php echo $memberCount; ?></strong> members, each member can use their<br>email and password to login and view their accounts from their phone</p>

                            <?php if (!empty($errorMessage)): ?>
                                <p class="error-text"><?php echo $errorMessage; ?></p>
                            <?php endif; ?>

                            <div class="memberSearchSection">
                                <div class="search-input-wrapper">
                                    <svg class="search-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="#9ca3af" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="11" cy="11" r="7"></circle>
                                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                    </svg>
                                    <input type="text" name="memberSearch" id="memberSearch" autocomplete="off" placeholder="Find by name, email, phone" oninput="onMemberSearchInput(this.value)">
                                    <button type="button" id="memberSearchClearBtn" class="search-clear-btn" onclick="clearMemberSearch()" title="Clear">
                                        <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="#ffffff" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                    </button>
                                    <span id="memberSearchSpinner" class="search-spinner"></span>
                                </div>
                            </div>

                            <div class="member-cards-grid" id="memberCardsGrid">
                                <?php foreach ($members as $index => $m): ?>
                                <div class="member-card">
                                    <div class="member-card-header">
                                        <div class="user-meta">
                                            <div class="avatar-circle">
                                                <svg viewBox="0 0 38 38" width="38" height="38">
                                                    <circle cx="19" cy="19" r="19" fill="#F87171"/>
                                                    <circle cx="19" cy="15" r="7" fill="#FED7AA"/>
                                                    <path d="M7 34c0-6 5-9 12-9s12 3 12 9" fill="#FEE2E2"/>
                                                </svg>
                                            </div>
                                            <div class="user-text-info">
                                                <h4><?php echo $m['Name']; ?></h4>
                                                <span class="role-badge"><?php echo $m['Role']; ?></span>
                                            </div>
                                        </div>
                                        <?php if ($m['Role'] != 'Manager'): ?>
                                            <button type="button" class="remove-btn" onclick="openDeleteModal('<?php echo $m['userId']; ?>')">Remove</button>
                                        <?php endif; ?>
                                    </div>

                                    
                                    <div class="member-detail-line copy-email-row">
                                        <span class="email-left">
                                            <strong>Email:</strong> <span id="email_<?php echo $index; ?>"><?php echo $m['Email']; ?></span>
                                        </span>
                                        <button type="button" class="copy-btn" onclick="copyEmail('email_<?php echo $index; ?>')" title="Copy Email">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                                            </svg>
                                        </button>
                                    </div>

                                    <div class="member-detail-line"><strong>Phone:</strong> <?php echo !empty($m['Phone']) ? $m['Phone'] : 'N/A'; ?></div>

                                    
                                    <?php
                                        $memberBazarDates = isset($bazarDatesMap[$m['userId']]) ? $bazarDatesMap[$m['userId']] : array();
                                        $bazarCount = count($memberBazarDates);

                                        $hasFutureBazarDates = false;
                                        foreach($memberBazarDates as $bd){
                                            if($bd >= $todayStr){
                                                $hasFutureBazarDates = true;
                                                break;
                                            }
                                        }
                                    ?>
                                    <div class="row-action-section">
                                        <span>Bazar Dates</span>
                                        <div class="action-icons">
                                            <?php if($bazarCount > 0): ?>
                                                <span class="no-data-tag"><?php echo $bazarCount; ?> Date<?php echo $bazarCount > 1 ? 's' : ''; ?> Set</span>
                                            <?php else: ?>
                                                <span class="no-data-tag">No Bazar Date Set</span>
                                            <?php endif; ?>

                                            <?php if($myRole == 'Manager'): ?>
                                                <button type="button" class="icon-action-btn" onclick='openCalendarModal(<?php echo json_encode($m["userId"]); ?>, <?php echo json_encode(array_values($memberBazarDates)); ?>)' title="Edit Bazar Dates">
                                                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="#2b2b2b" stroke-width="2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                                                </button>
                                                <?php if($bazarCount > 0 && $hasFutureBazarDates): ?>
                                                <button type="button" class="icon-action-btn" onclick="openBazarDeleteModal('<?php echo $m['userId']; ?>')" title="Delete Upcoming Bazar Dates">
                                                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="#2b2b2b" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                                </button>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>

                            <p id="memberSearchNoResults" class="no-results-text" style="display: none;">No member matched your search.</p>
                        </div>

                        
                        <div id="addMemberSection" class="tab-content<?php echo ($activeTab == 'addMember') ? ' active' : ''; ?>">

                            <?php if (!empty($errorMessage)): ?>
                                <p class="error-text"><?php echo $errorMessage; ?></p>
                            <?php endif; ?>

                            <form method="POST" action="">
                                <div class="form-group">
                                    <label>Member Name</label>
                                    <input type="text" name="member_name" id="newMemberName" class="form-control" placeholder="Md Rahim" value="<?php echo $memberName; ?>" required>
                                </div>

                                <div class="form-group">
                                    <label>Member Email Address</label>
                                    <div class="input-with-icon">
                                        <input type="email" name="member_email" id="newMemberEmail" class="form-control" placeholder="apud@gmail.com or apud@mm.app" value="<?php echo $memberEmail; ?>" oninput="onMemberEmailInput(this.value)" required>
                                    </div>
                                </div>

                                <div class="warning-tag-line">
                                    <span>⚠️</span>
                                    <span>একাউন্ট আগে থেকে থাকলে সেটাই মেসে যোগ হবে (নাম ডাটাবেজ থেকে নেওয়া হবে); না থাকলে নতুন একাউন্ট তৈরি হবে, ডিফল্ট পাসওয়ার্ড: <mark>12345</mark></span>
                                </div>

                                <button type="submit" name="add_member_submit" class="primary-btn">Add Member</button>
                            </form>

                            <a href="https://youtu.be/4e84b-09E5A" target="_blank" class="yt-banner">
                                <div class="yt-left">
                                    <h3>দেখুন কিভাবে<br>মেসে মেম্বার যুক্ত<br>করবেন</h3>
                                    <div class="yt-play-btn"><div class="yt-play-icon"></div></div>
                                </div>
                                <div class="yt-phone-mockup">
                                    <div class="phone-screen-bar"></div>
                                    <div class="phone-screen-banner"></div>
                                    <div class="phone-screen-card"></div>
                                </div>
                            </a>
                        </div>
                    </div>

                    
                    <?php if($myRole == 'Manager'): ?>
                    <div id="calendarModal" class="modal-overlay">
                        <div class="modal-card">
                            <h3 class="modal-card-title" style="text-align: center;">Edit Bazar Date</h3>

                            <form method="POST" action="" id="bazarForm">
                                <input type="hidden" name="bazar_user_id" id="calBazarUserId">
                                <input type="hidden" name="bazar_dates" id="calBazarDatesInput">

                                <div class="calendar-header">
                                    <span onclick="shiftCalendarMonth(-1)" style="cursor:pointer;">&lt;</span>
                                    <span id="calendarMonthLabel"></span>
                                    <span onclick="shiftCalendarMonth(1)" style="cursor:pointer;">&gt;</span>
                                </div>
                                <div class="calendar-grid" id="calendarGrid">
                                    <span class="day-label">Su</span><span class="day-label">Mo</span><span class="day-label">Tu</span><span class="day-label">We</span><span class="day-label">Th</span><span class="day-label">Fr</span><span class="day-label">Sa</span>
                                </div>

                                <div class="calendar-legend">
                                    <span><i class="legend-dot selected"></i> Selected</span>
                                    <span><i class="legend-dot locked"></i> Past (locked)</span>
                                    <span><i class="legend-dot taken"></i> Taken by other</span>
                                </div>

                                <div class="modal-btn-row">
                                    <button type="button" class="btn-cancel" onclick="closeCalendarModal()">Cancel</button>
                                    <button type="submit" name="assign_bazar_submit" class="btn-black" onclick="return prepareBazarSubmit()">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <?php endif; ?>

                    
                    <?php if($myRole == 'Manager'): ?>
                    <div id="deleteBazarModal" class="modal-overlay">
                        <div class="modal-card">
                            <h3 class="modal-card-title" style="text-align: center;">Are you sure?</h3>
                            <p class="modal-card-subtitle" style="text-align: center;">This will remove all bazar dates set for this member.</p>
                            <form method="POST" action="">
                                <input type="hidden" id="delBazarUserId" name="bazar_user_id">
                                <div class="modal-btn-row">
                                    <button type="button" class="btn-cancel" onclick="closeBazarDeleteModal()">Cancel</button>
                                    <button type="submit" name="remove_bazar_submit" class="btn-black">Delete</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <?php endif; ?>

                    
                    











                    <div id="permSuccessModal" class="modal-overlay">
                        <div class="modal-card styled-gray">
                            <div style="text-align: center; margin-bottom: 18px;">
                                <svg viewBox="0 0 200 200" width="150" height="150" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="100" cy="100" r="65" fill="#D2F3DE" />
                                    <circle cx="45" cy="65" r="14" fill="#69E299" opacity="0.6"/>
                                    <circle cx="155" cy="65" r="14" fill="#69E299" opacity="0.6"/>
                                    <circle cx="45" cy="135" r="14" fill="#69E299" opacity="0.6"/>
                                    <circle cx="155" cy="135" r="14" fill="#69E299" opacity="0.6"/>
                                    <ellipse cx="100" cy="175" rx="35" ry="10" fill="#E2E8F0" />
                                    <rect x="90" y="115" width="20" height="45" fill="#202938" rx="4"/>
                                    <polygon points="80,85 120,85 110,120 90,120" fill="#4ADE80" />
                                    <polygon points="98,85 102,85 104,115 96,115" fill="#FACC15" />
                                    <circle cx="100" cy="68" r="14" fill="#FFD8B3" />
                                    <path d="M 88 65 Q 100 50 112 65 Q 108 55 92 56 Z" fill="#2D3748" />
                                </svg>
                            </div>

                            <h3 class="modal-card-title">স্পেশাল পারমিশন যুক্ত হয়েছে!</h3>
                            <p class="modal-card-subtitle">Special permission added successfully</p>

                            <div class="modal-btn-row">
                                <a href="activeMonthDetails.php" class="btn-cancel">মাসের বিস্তারিত হিসাব</a>
                                <a href="../home.php" class="btn-red">Go to Home</a>
                            </div>
                        </div>
                    </div>

                    
                    <div id="deleteConfirmModal" class="modal-overlay">
                        <div class="modal-card">
                            <div class="delete-illus">
                                <svg viewBox="0 0 160 140" width="120" height="100" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M 70 20 Q 95 20 95 45 Q 95 65 80 75 L 80 85" fill="none" stroke="#F87171" stroke-width="12" stroke-linecap="round"/>
                                    <circle cx="80" cy="105" r="7" fill="#F87171"/>
                                    <ellipse cx="40" cy="125" rx="15" ry="5" fill="#E2E8F0"/>
                                    <circle cx="40" cy="55" r="8" fill="#5A3E2B"/>
                                    <rect x="35" y="65" width="10" height="25" fill="#F87171" rx="2"/>
                                    <rect x="35" y="90" width="10" height="28" fill="#1E293B" rx="2"/>
                                </svg>
                            </div>
                            <h3 class="modal-card-title">Are you sure?</h3>
                            <p class="modal-card-subtitle">Are you sure, you want to do this?</p>
                            <form method="POST" action="">
                                <input type="hidden" id="delUserId" name="delete_user_id">
                                <div class="modal-btn-row">
                                    <button type="button" class="btn-cancel" onclick="closeDeleteModal()">Cancel</button>
                                    <button type="submit" name="remove_member_submit" class="btn-black">Delete</button>
                                </div>
                            </form>
                        </div>
                    </div>


                    

                </div>
                <div class="footer_section"><?php include __DIR__ . "/../layout/footer.php";?></div>
            </div>
        </div>
    </div>




 



    <script>


        var allBazarAssignments = <?php echo json_encode($allBazarAssignments); ?>;
        var todayDateStr = <?php echo json_encode($todayStr); ?>;
    </script>
    <script src="/app/view/assets/js/messMember.js"></script>

<?php if (!empty($showSuccessModal)): ?><div class="success-popup"><div id="memberAddedModal" class="modal-overlay active">
        <div class="modal-card">
            
            <div class="modal-illustration">
                <svg viewBox="0 0 240 200" width="200" height="170" xmlns="http://www.w3.org/2000/svg">
                    
                    <circle cx="120" cy="100" r="70" fill="#dcfce7" />
                    
                    
                    
                    <g transform="translate(60, 45)">
                        <rect x="0" y="8" width="12" height="18" fill="#4ade80" rx="2"/>
                        <path d="M 12 14 C 18 14, 22 10, 24 4 C 26 0, 29 2, 28 8 L 27 14 L 32 14 C 35 14, 35 18, 33 20 C 35 21, 35 24, 32 25 C 34 26, 33 29, 30 29 L 12 29 Z" fill="#ffffff" stroke="#cbd5e1" stroke-width="1"/>
                    </g>
                    
                    <g transform="translate(155, 45) scale(-1, 1)">
                        <rect x="-24" y="8" width="12" height="18" fill="#4ade80" rx="2"/>
                        <path d="M -12 14 C -6 14, -2 10, 0 4 C 2 0, 5 2, 4 8 L 3 14 L 8 14 C 11 14, 11 18, 9 20 C 11 21, 11 24, 8 25 C 10 26, 9 29, 6 29 L -12 29 Z" fill="#ffffff" stroke="#cbd5e1" stroke-width="1"/>
                    </g>
                    
                    <g transform="translate(62, 115)">
                        <rect x="0" y="8" width="12" height="18" fill="#4ade80" rx="2"/>
                        <path d="M 12 14 C 18 14, 22 10, 24 4 C 26 0, 29 2, 28 8 L 27 14 L 32 14 C 35 14, 35 18, 33 20 C 35 21, 35 24, 32 25 C 34 26, 33 29, 30 29 L 12 29 Z" fill="#ffffff" stroke="#cbd5e1" stroke-width="1"/>
                    </g>
                    
                    <g transform="translate(155, 115) scale(-1, 1)">
                        <rect x="-24" y="8" width="12" height="18" fill="#4ade80" rx="2"/>
                        <path d="M -12 14 C -6 14, -2 10, 0 4 C 2 0, 5 2, 4 8 L 3 14 L 8 14 C 11 14, 11 18, 9 20 C 11 21, 11 24, 8 25 C 10 26, 9 29, 6 29 L -12 29 Z" fill="#ffffff" stroke="#cbd5e1" stroke-width="1"/>
                    </g>

                    
                    <ellipse cx="120" cy="180" rx="35" ry="7" fill="#e2e8f0" />

                    
                    <path d="M 112 110 L 118 110 L 115 175 L 108 175 Z" fill="#1e293b"/>
                    <path d="M 122 110 L 128 110 L 132 175 L 125 175 Z" fill="#1e293b"/>
                    <ellipse cx="106" cy="176" rx="6" ry="3" fill="#0f172a"/>
                    <ellipse cx="134" cy="176" rx="6" ry="3" fill="#0f172a"/>

                    
                    <polygon points="105,75 135,75 130,115 110,115" fill="#4ade80" />
                    <polygon points="118,78 122,78 123,108 117,108" fill="#eab308" />
                    <path d="M 105 78 L 94 95 L 102 98 L 110 85" fill="#4ade80"/>
                    <path d="M 135 78 L 146 95 L 138 98 L 130 85" fill="#4ade80"/>

                    
                    <circle cx="120" cy="62" r="10" fill="#fed7aa" />
                    <path d="M 112 60 Q 120 48 128 60 Q 126 52 115 54 Z" fill="#1e293b" />
                </svg>
            </div>

            
            <h2 class="modal-title"><?php echo htmlspecialchars($successTitle); ?></h2>
            <p class="modal-subtitle"><?php echo htmlspecialchars($successSubtitle); ?></p>

            
            <div class="modal-btn-row">
                <a href="messMember.php" class="btn-primary-dark">Ok, Great!</a>
            </div>
        </div>
    </div><script>
        function closeModalAndAddMore() {
            
            
        }
    </script></div><?php endif; ?>
