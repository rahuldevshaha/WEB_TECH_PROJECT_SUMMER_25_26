<link rel="stylesheet" href="/app/view/assets/css/successPopups.css">

    <link rel="stylesheet" href="/app/view/assets/css/startNewMonth.css">
    <link rel="stylesheet" href="/app/view/assets/css/home.css">



    
    <div id="home_page">
        <div class="nav"><?php include "../layout/navbar.php"; ?></div>
        <div class="body_section">
            <div class="sidebar_section"> <?php include "../layout/sidebar.php";?></div>
            <div class="content_section">
                <div id="content_body">


                    
    <div class="main-container">
        
        <div class="warning-icon-box">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
        </div>

        <h1 class="page-title">নতুন মাসের হিসাব শুরু করুন</h1>
        <p class="page-subtitle">নতুন হিসাব শুরু করলে,চলতি মাসের হিসাব আর <mark>এডিট করা যাবে না</mark></p>

        <?php if (!empty($errorMessage)): ?>
            <p class="error-text"><?php echo $errorMessage; ?></p>
        <?php endif; ?>

        <div class="form-wrapper">
            
            <div class="form-group">
                <label>নতুন মাসের নাম</label>
                <input type="text" id="newMonthInput" class="form-control" placeholder="e.g. April 2027" autocomplete="off" onfocus="showSuggestions()">
                
                <div id="suggestionsList" class="suggestions-dropdown">
                    <div class="suggestion-item" onclick="selectMonth('August')">August</div>
                    <div class="suggestion-item" onclick="selectMonth('November')">November</div>
                    <div class="suggestion-item" onclick="selectMonth('september')">september</div>
                    <div class="suggestion-item" onclick="selectMonth('april')">april</div>
                </div>
            </div>

            <div class="checkbox-row">
                <input type="checkbox" id="carryBalanceCheckbox" onchange="toggleCarryInfo(this)">
                <label for="carryBalanceCheckbox"><?php echo htmlspecialchars($activeMonth); ?> মাসের মেম্বারদের ব্যালেন্স, নতুন মাসে ট্রান্সফার করুন।</label>
            </div>

            <div id="balanceInfoBox" class="carry-balance-info">
                রানিং মাসের মেম্বারদের ব্যালেন্স, নতুন মাসে মেম্বারদের একাউন্টে অটোমেটিক্যালি জমা করা হবে।
            </div>

            <button type="button" class="primary-btn" onclick="openConfirmModal()">নতুন হিসাব চালু</button>
        </div>
    </div>

    
    <div id="confirmModal" class="modal-overlay">
        <div class="modal-card">
            <h2 class="modal-title-left">নতুন মাস শুরু করবেন?</h2>
            <p class="modal-desc">নতুন মাস চালু করলে রানিং হিসাব ক্লোজ হবে</p>
            
            <div>
                <span class="security-badge-note">টাইপ করুনঃ Start new month</span>
            </div>

            <form method="POST" action="" onsubmit="return validateSecurityPhrase()">
                <input type="hidden" id="hiddenMonthName" name="new_month_name">
                <input type="hidden" id="hiddenCarryBalance" name="carry_balance">

                <input type="text" id="securityPhraseInput" name="security_phrase" class="modal-input" placeholder="Start new month" required>
                <p id="startMonthFormError" class="error-text" style="display:none;"></p>

                <div class="modal-btn-row">
                    <button type="button" class="btn-cancel" onclick="closeConfirmModal()">Cancel</button>
                    <button type="submit" name="confirm_start_month" class="btn-red">নতুন মাস চালু</button>
                </div>
            </form>
        </div>
    </div>

    
    <div id="successModal" class="modal-overlay <?php if($showSuccessModal) echo 'active'; ?>">
        <div class="modal-card center-content">
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
            <h2 class="page-title" style="color: #111827; margin-bottom: 6px;">Wow!</h2>
            <p class="modal-desc" style="margin-bottom: 24px;">New month started successfully</p>

            <div class="modal-btn-row" style="justify-content: flex-end;">
                <a href="#" data-url="components/allMonthDetails.php" class="btn-cancel">Close</a>
            </div>
        </div>
    </div>



                </div>
                <div class="footer_section"><?php include "../layout/footer.php";?></div>
            </div>
        </div>
    </div>






    <script>
        function showSuggestions() {
            document.getElementById('suggestionsList').classList.add('show');
        }

        function selectMonth(name) {
            document.getElementById('newMonthInput').value = name;
            document.getElementById('suggestionsList').classList.remove('show');
        }

        document.addEventListener('click', function(e) {
            const input = document.getElementById('newMonthInput');
            const dropdown = document.getElementById('suggestionsList');
            if (!input.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.remove('show');
            }
        });

        function toggleCarryInfo(checkbox) {
            const infoBox = document.getElementById('balanceInfoBox');
            if (checkbox.checked) {
                infoBox.classList.add('show');
            } else {
                infoBox.classList.remove('show');
            }
        }

        function showStartMonthError(message) {
            const errorBox = document.getElementById('startMonthFormError');
            if (errorBox) {
                errorBox.textContent = message;
                errorBox.style.display = 'block';
            }
        }

        function openConfirmModal() {
            const monthVal = document.getElementById('newMonthInput').value.trim();
            if (!monthVal) {
                alert('দয়া করে মাসের নাম লিখুন।');
                return;
            }

            
            document.getElementById('hiddenMonthName').value = monthVal;
            document.getElementById('startMonthFormError').style.display = 'none';
            if (document.getElementById('carryBalanceCheckbox').checked) {
                document.getElementById('hiddenCarryBalance').value = "1";
            } else {
                document.getElementById('hiddenCarryBalance').value = "";
            }

            document.getElementById('confirmModal').classList.add('active');
        }

        function closeConfirmModal() {
            document.getElementById('confirmModal').classList.remove('active');
        }

        function validateSecurityPhrase() {
            const phrase = document.getElementById('securityPhraseInput').value.trim();
            document.getElementById('startMonthFormError').style.display = 'none';
            if (phrase.toLowerCase() !== 'start new month') {
                showStartMonthError('অনুগ্রহ করে সঠিক নিরাপত্তা বাক্যটি টাইপ করুন: Start new month');
                return false;
            }
            return true;
        }
    </script>
