<link rel="stylesheet" href="/app/view/assets/css/successPopups.css">


    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/app/view/assets/css/messSetting.css">
    <link rel="stylesheet" href="/app/view/assets/css/home.css">



    <div id="home_page">
        <div class="nav"><?php include __DIR__ . "/../layout/navbar.php"; ?></div>
        <div class="body_section">
            <div class="sidebar_section"> <?php include __DIR__ . "/../layout/sidebar.php";?></div>
            <div class="content_section">
                <div id="content_body">


                    <div class="settings-card">
                    <h2 class="form-title">Mess Settings</h2>

                    <?php if (!empty($errorMessage)): ?>
                        <p class="error-text"><?php echo $errorMessage; ?></p>
                    <?php endif; ?>

                    <form id="messSettingsForm" method="POST">
                        
                        <div class="form-group">
                            <label class="form-label" for="messName">মেসের নাম</label>
                            <input 
                                type="text" 
                                id="messName" 
                                name="mess_name" 
                                class="input-control" 
                                value="<?php echo $messName; ?>"
                                required
                            >
                        </div>

                        
                        <div class="form-group">
                            <label class="form-label" for="messCurrency">মেসের মুদ্রা</label>
                            <input 
                                type="text" 
                                id="messCurrency" 
                                name="mess_currency" 
                                class="input-control" 
                                value="<?php echo $messCurrency; ?>"
                                disabled
                                required
                            >
                        </div>

                        <div class="form-group">


                        <div class="transferBal">
                                                        <input
                                type="checkbox"
                                id="autoTranferBal"
                                name="autoTranferBal"
                                class="input-control"
                                <?php echo $autoTranferBal ? "checked" : ""; ?>
                            >
                            <label class="form-label" for="autoTranferBal">Auto Tranfer Balance to Next Month</label>
                        </div>

                        </div>



                        
                        <button type="submit" class="btn-submit">পরিবর্তন করুন</button>
                    </form>
                    </div>



                </div>
                <div class="footer_section"><?php include __DIR__ . "/../layout/footer.php";?></div>
            </div>
        </div>
    </div>

<?php if (!empty($showSuccessModal)): ?><div class="success-popup"><div id="settingsSuccessModal" class="modal-overlay active">
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

            
            <h2 class="modal-title">Wow!</h2>
            <p class="modal-subtitle">Mess updated successfully</p>

            
            <div class="modal-btn-row">
                <button type="button" class="btn-close" onclick="closeModal()">OK, Great!</button>
            </div>
        </div>
    </div><script>
        function closeModal() {
            document.getElementById('settingsSuccessModal').classList.remove('active');
            window.location.href = 'messSetting.php';
        }
    </script></div><?php endif; ?>
