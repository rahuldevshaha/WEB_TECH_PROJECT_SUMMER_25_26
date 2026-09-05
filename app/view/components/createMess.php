<link rel="stylesheet" href="/app/view/assets/css/successPopups.css">

    <link rel="stylesheet" href="/app/view/assets/css/createMess.css">
    <link rel="stylesheet" href="/app/view/assets/css/home.css">



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


                    
                    <?php if (!empty($showModal)): ?><div class="success-popup"><div id="messSuccessModal" class="modal-overlay active">
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

            
            <h2 class="modal-title">আপনাদের মেস সফলভাবে খোলা হয়েছে!</h2>
            <p class="modal-subtitle">Mess created successfully</p>

            
            <div class="modal-btn-row">
                <button type="button" class="btn-ok-great" onclick="redirectToDashboard()">Ok, Great</button>
            </div>
        </div>
    </div><script>
        function redirectToDashboard() {
            window.location.href = '../home.php';
        }
    </script></div><?php endif; ?>


                    
                </div>

                <div class="footer_section">
                <?php include __DIR__ . "/../layout/footer.php";?>
        </div>
            </div>
        </div>


    </div>

    
<?php if (!empty($showDeleteSuccessModal)): ?><div class="success-popup"><div id="deleteSuccessModal" class="modal-overlay active">
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

            
            <h2 class="modal-title">সফল ভাবে মেস এবং সকল হিসাব ডিলেট হয়ে গেছে!</h2>

            
            <div class="modal-btn-row">
                <button type="button" class="btn-close" onclick="closeModal()">Close</button>
            </div>
        </div>
    </div><script>
        function closeModal() {
            document.getElementById('deleteSuccessModal').classList.remove('active');
            window.location.href = 'createMess.php';
        }
    </script>

</div><?php endif; ?>
