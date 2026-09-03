
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/app/assets/css/ChangeMessManager.css">
    <link rel="stylesheet" href="/app/assets/css/home.css">


        
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

                        <?php if(!empty($msg)): ?>
                            <p class="success-text"><?php echo $msg; ?></p>
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
