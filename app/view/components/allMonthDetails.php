

    
    <link rel="stylesheet" href="/app/view/assets/css/allMonthDetails.css">
    <link rel="stylesheet" href="/app/view/assets/css/home.css">


        
    <div id="home_page">
        <div class="nav"><?php include "../layout/navbar.php"; ?></div>
        <div class="body_section">
            <div class="sidebar_section"> <?php include "../layout/sidebar.php";?></div>
            <div class="content_section">
                <div id="content_body">


                    
    <div class="main-container">
        <h1 class="page-title">Month List</h1>

        
        <div class="month-card">
            <div class="month-left">
                <div class="calendar-icon-box">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                </div>
                <div class="month-info">
                    <span class="month-name"><?php echo htmlspecialchars($activeMonth); ?></span>
                    <div class="month-date-row">
                        <span><?php echo $startDate; ?> - Running</span>
                    </div>
                    <div>
                        <span class="status-badge">Active</span>
                    </div>
                </div>
            </div>

            <div class="month-actions">
                
                <button type="button" class="icon-btn" onclick="openNameModal()" title="Edit Month Name">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="#2b2b2b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                    </svg>
                </button>

                
                <button type="button" class="icon-btn" onclick="openDetailsModal()" title="View Month Details">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="#2b2b2b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                </button>
            </div>
        </div>

        <a href="createMess.php" class="start-month-btn">Start new month</a>
    </div>

    
    <div id="detailsModal" class="modal-overlay">
        <div class="modal-card">
            <h2 class="modal-title">Details of <?php echo htmlspecialchars($activeMonth); ?></h2>
            <p class="modal-subtitle"><?php echo htmlspecialchars($activeMonth); ?> মাসের সকল হিসাব বিস্তারিত হিসাব দেখা যাবে</p>
            
            <div class="modal-stacked-actions">
                <a href="activeMonthDetails.php" class="btn-red">View Details</a>
                <button type="button" class="btn-cancel" onclick="closeDetailsModal()">Cancel</button>
            </div>
        </div>
    </div>

    
    <div id="editNameModal" class="modal-overlay">
        <div class="modal-card">
            <h2 class="modal-title">Change Month Name</h2>
            <p class="modal-subtitle">Changing the name does not affect the month details</p>

            <form method="POST" action="">
                <div class="modal-form-group">
                    <label>Enter Month name (Enter the name of the month you want to manage)</label>
                    <input type="text" name="month_name" class="modal-input" value="<?php echo htmlspecialchars($activeMonth); ?>" required>
                </div>

                <div class="modal-btn-row">
                    <button type="button" class="btn-cancel" onclick="closeNameModal()">Cancel</button>
                    <button type="submit" name="update_month_name" class="btn-red">Change Name</button>
                </div>
            </form>
        </div>
    </div>

    
    <div id="successModal" class="modal-overlay <?php if($showSuccessModal) echo 'active'; ?>">
        <div class="modal-card">
            <div class="success-illus">
                <svg viewBox="0 0 200 200" width="130" height="130" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="100" cy="100" r="60" fill="#D2F3DE" />
                    <circle cx="50" cy="70" r="12" fill="#69E299" opacity="0.6"/>
                    <circle cx="150" cy="70" r="12" fill="#69E299" opacity="0.6"/>
                    <ellipse cx="100" cy="170" rx="30" ry="8" fill="#E2E8F0" />
                    <rect x="92" y="115" width="16" height="40" fill="#202938" rx="3"/>
                    <polygon points="84,90 116,90 108,120 92,120" fill="#4ADE80" />
                    <polygon points="98,90 102,90 103,115 97,115" fill="#FACC15" />
                    <circle cx="100" cy="75" r="12" fill="#FFD8B3" />
                </svg>
            </div>
            <h2 class="modal-title">Wow!</h2>
            <p class="modal-subtitle">Month updated successfully</p>

            <div class="modal-btn-row" style="justify-content: flex-end;">
                <a href="allMonthDetails.php" class="btn-cancel">Close</a>
            </div>
        </div>
    </div>




                </div>
                <div class="footer_section"><?php include "../layout/footer.php";?></div>
            </div>
        </div>
    </div>






    <script>
        function openDetailsModal() {
            document.getElementById('detailsModal').classList.add('active');
        }

        function closeDetailsModal() {
            document.getElementById('detailsModal').classList.remove('active');
        }

        function openNameModal() {
            document.getElementById('editNameModal').classList.add('active');
        }

        function closeNameModal() {
            document.getElementById('editNameModal').classList.remove('active');
        }
    </script>
