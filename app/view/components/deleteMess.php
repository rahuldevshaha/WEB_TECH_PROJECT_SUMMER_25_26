
    
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/app/assets/css/deleteMess.css">
    <link rel="stylesheet" href="/app/assets/css/home.css">

    
    <div id="home_page">
        <div class="nav"><?php include __DIR__ . "/../layout/navbar.php"; ?></div>
        <div class="body_section">
            <div class="sidebar_section"> <?php include __DIR__ . "/../layout/sidebar.php";?></div>
            <div class="content_section">
                <div id="content_body">


                
                    <div class="delete-container">
                        
                        <div class="warning-icon-wrapper">
                        <div class="warning-diamond">
                                <span class="exclamation">!</span>
                            </div>
                        </div>

                        
                        <h2 class="title">মেস ডিলেট</h2>

                        
                        <ul class="warning-list">
                            <li>মেস ডিলেট করলে মেসের <span class="highlight">সকল মাসের হিসাব ডিলেট</span> হয়ে যাবে এবং সকল মেম্বার ও রিমুভ হয়ে যাবে</li>
                            <li>তাই ডিলেট করার পূর্বে অবশ্যই <span class="highlight">সিউর হয়ে নিন</span></li>
                            <li>If you delete your current mess. all member will be <span class="highlight">removed</span>, all data will be <span class="highlight">deleted</span></li>
                        </ul>

                        
                        <button type="button" class="btn-delete" id="openModalBtn">মেস ডিলেট করুন</button>
                    </div>

                    
                    <div class="modal-overlay" id="deleteModal">
                        <div class="modal-box">
                            <h3 class="modal-title">মেস ডিলেট করে ফেলবেন?</h3>
                            <p class="modal-subtitle">মেস ডিলেট করতে চাইলে নিচের বক্স এ টাইপ করুন:-</p>

                            <form method="POST" id="confirmDeleteForm">
                                <?php if (!empty($errorMessage)): ?>
                                    <p class="error-text"><?php echo $errorMessage; ?></p>
                                <?php endif; ?>
                                <div class="form-group">
                                    <label class="form-label" for="confirmText"> Type "Delete all months calculation"</label>
                                    <input 
                                        type="text" 
                                        id="confirmText" 
                                        name="confirmText" 
                                        class="modal-input" 
                                        value="<?php echo $confirmText; ?>"
                                        placeholder="e.g. Delete all months calculation" 
                                        autocomplete="off"
                                    >
                                </div>

                                
                                <div class="alert-box">
                                    <svg class="info-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="12" y1="16" x2="12" y2="12"></line>
                                        <line x1="12" y1="8" x2="12.01" y2="8"></line>
                                    </svg>
                                    <span>মেস ডিলেট করে সব হিসাব ডিলেট করে ফেললে, সেই হিসাব ফেরত পেতে চাইলে 200 টাকা জরিমানা দিতে হবে। তাই মেস ডিলিট করার আগে sure হন।</span>
                                </div>

                                
                                <div class="modal-actions">
                                    <button type="button" class="btn-cancel" id="cancelBtn">Cancel</button>
                                    <button type="submit" class="btn-confirm-delete">ডিলেট মেস</button>
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
        const openModalBtn = document.getElementById('openModalBtn');
        const deleteModal = document.getElementById('deleteModal');
        const cancelBtn = document.getElementById('cancelBtn');

        
        openModalBtn.addEventListener('click', () => {
            deleteModal.classList.add('active');
        });

        
        cancelBtn.addEventListener('click', () => {
            deleteModal.classList.remove('active');
        });

        
        deleteModal.addEventListener('click', (e) => {
            if (e.target === deleteModal) {
                deleteModal.classList.remove('active');
            }
        });
    </script>
