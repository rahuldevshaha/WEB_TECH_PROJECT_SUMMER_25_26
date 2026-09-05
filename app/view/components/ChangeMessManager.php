<link rel="stylesheet" href="/app/view/assets/css/successPopups.css">

    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/app/view/assets/css/ChangeMessManager.css">
    <link rel="stylesheet" href="/app/view/assets/css/home.css">


        
    <div id="home_page">
        <div class="nav"><?php include __DIR__ . "/../layout/navbar.php"; ?></div>
        <div class="body_section">
            <div class="sidebar_section"> <?php include __DIR__ . "/../layout/sidebar.php";?></div>
            <div class="content_section">
                <div id="content_body">


                    
                    <div class="manager-card">
                        
                        <div class="card-header">
                            <h2 class="title">Change Mess Manager</h2>
                            <p class="subtitle">Select new manager</p>
                        </div>

                        <?php if(!empty($errorMessage)): ?>
                            <p class="error-text"><?php echo $errorMessage; ?></p>
                        <?php endif; ?>

                        <?php if(count($otherMembers) == 0): ?>

                            <p class="empty-text">No Other Members In This Mess To Make Manager!</p>

                        <?php else: ?>

                        
                        <form id="changeManagerForm" method="POST">
                            <div class="form-group">
                                <label class="form-label" for="managerSelect">Select Manager</label>
                                <div class="select-wrapper">
                                    <select id="managerSelect" name="new_manager_id" class="input-control" required>
                                        <option value="" disabled selected hidden>Select Manager</option>
                                        <?php foreach($otherMembers as $om): ?>
                                            <option value="<?php echo $om["userId"]; ?>"><?php echo $om["Name"]; ?> (<?php echo $om["Email"]; ?>)</option>
                                        <?php endforeach; ?>
                                    </select>
                                    
                                    <div class="select-arrow">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="6 9 12 15 18 9"></polyline>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn-submit">Update</button>
                        </form>

                        <?php endif; ?>

                        
                        <div class="tutorial-banner">
                            <div class="banner-left">
                                <h3 class="banner-text">
                                    দেখুন কিভাবে<br>মেসের ম্যানেজার<br>চেঞ্জ করবেন
                                </h3>
                                
                                
                                <a href="https://www.youtube.com" target="_blank" class="play-btn" title="Watch Tutorial">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="#ff5252">
                                        <polygon points="6 3 20 12 6 21 6 3"></polygon>
                                    </svg>
                                </a>
                            </div>

                            
                            <div class="banner-right">
                                <div class="phone-frame">
                                    <div class="phone-notch"></div>
                                    <div class="phone-screen">
                                        
                                        <div class="yt-header">
                                            <span class="yt-back">&#8592;</span>
                                            <span class="yt-title">Mess Manager : মে...</span>
                                        </div>
                                        
                                        <div class="yt-banner">
                                            <span>Mess manager</span>
                                        </div>
                                        
                                        <div class="yt-profile">
                                            <div class="yt-avatar"></div>
                                            <div class="yt-name">Mess Manager : মেসের যাবতীয় হিসাব...</div>
                                        </div>
                                        
                                        <div class="yt-video-card">
                                            <div class="yt-thumb"></div>
                                            <div class="yt-info">
                                                <div class="yt-v-title">How To Use Mess Manager</div>
                                                <div class="yt-v-sub">Part 1 : Create mess & Add member</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



                </div>
                <div class="footer_section"><?php include __DIR__ . "/../layout/footer.php";?></div>
            </div>
        </div>
    </div>






    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const managerSelect = document.getElementById('managerSelect');

            
            if (managerSelect) {
                managerSelect.addEventListener('change', () => {
                    if (managerSelect.value) {
                        managerSelect.classList.add('selected');
                    }
                });
            }
        });
    </script>

<?php if (!empty($showSuccessModal)): ?><div class="success-popup"><div id="managerSuccessModal" class="modal-overlay active">
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

            
            <h2 class="modal-title">Manager Changed Successfully!</h2>
            <p class="modal-subtitle">Mess manager changed successfully</p>

            
            <div class="modal-btn-row">
                <button type="button" class="btn-close" onclick="closeModal()">OK, Great!</button>
            </div>
        </div>
    </div><script>
        function closeModal() {
            document.getElementById('managerSuccessModal').classList.remove('active');
            window.location.href = '../home.php';
        }
    </script></div><?php endif; ?>
