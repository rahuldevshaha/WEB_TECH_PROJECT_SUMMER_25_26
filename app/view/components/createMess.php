
    <link rel="stylesheet" href="/app/assets/css/createMess.css">
    <link rel="stylesheet" href="/app/assets/css/home.css">



        <div id="home_page">
        <div class="nav">
                <?php include __DIR__ . "/../layout/navbar.php"; ?>
        </div>
        <div class="body_section">
            <div class="sidebar_section">
                <?php include __DIR__ . "/../layout/sidebar.php";?>
            </div>
            <div class="content_section">

                <div id="content_body">

                    
                    
                    <div class="form-container">

                            <div class="header-section">
                                <h2>আপনাদের মেসের হিসাব চালু করতে<br>প্রয়োজনীয় তথ্য গুলো প্রদান করুন</h2>
                            </div>

                            <?php if (!empty($errorMessage)): ?>
                                <p class="error-text"><?php echo $errorMessage; ?></p>
                            <?php endif; ?>

                            <form method="POST">
                                
                                <div class="form-group">
                                    <label for="messName">আপনাদের মেসের নাম দিন</label>
                                    <input type="text" id="messName" name="messName" placeholder="e.g. Our Mess" value="<?php echo $messName; ?>" required>
                                </div>


                                <div class="form-group">
                                    <label class="form-label" for="monthName">
                                        Select Month (যে মাসের হিসাব করবেন ওই মাসের নাম দিন)
                                    </label>

                                    <div class="input-wrapper">
                                        <input 
                                            type="date"
                                            id="monthName"
                                            class="input-control"
                                            name="monthName"
                                            value="<?php echo $monthName; ?>"
                                            min="<?= date('Y-m-01') ?>"
                                        >
                                    </div>
                                </div>

                                
                                <ul class="guidelines-list">
                                    <li>Select The Month।</li>
                                    <li>আমাদের এপ এ মাসের কোন <mark>নির্দিষ্ট দিন নেই।</mark></li>
                                    <li>মাসের হিসাব অটো বন্ধ হবে নাহ, যেকোনোদিন থেকেই হিসাব শুরু করে শেষ করতে পারবেন।</li>
                                    <li><mark>হিসাব শেষ না করলে</mark> ওই হিসাব চলতেই থাকবে। যেকোনো দিন থেকে হিসাব শুরু করে যেকোনো দিন হিসাব শেষ করা যাবে।</li>
                                </ul>



                                
                                <button type="submit" class="submit-btn">হিসাব শুরু করুন</button>
                            </form>
                    </div>


                    
                    <div id="successModal" class="modal-overlay <?php if($showModal) echo 'active'; ?>">
                        <div class="modal-card">
                            <div class="modal-illustration">
                                
                                <svg viewBox="0 0 200 200" width="160" height="160" xmlns="http://www.w3.org/2000/svg">
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
                            
                            <h3 class="modal-title">আপনাদের মেস সফলভাবে খোলা হয়েছে!</h3>
                            <p class="modal-subtitle">Mess created successfully</p>
                            
                            <div class="modal-actions">
                                <a href="../home.php" class="modal-btn" data-url="home">Ok, Great</a>
                            </div>
                    </div>


                    



                    </div>


                    
                </div>

                <div class="footer_section">
                <?php include __DIR__ . "/../layout/footer.php";?>
        </div>
            </div>
        </div>


    </div>



