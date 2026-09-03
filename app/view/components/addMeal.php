
    <link rel="stylesheet" href="/app/assets/css/addMeal.css">
    <link rel="stylesheet" href="/app/assets/css/home.css">


    <div id="home_page">
        <div class="nav"><?php include __DIR__ . "/../layout/navbar.php"; ?></div>
        <div class="body_section">
            <div class="sidebar_section"> <?php include __DIR__ . "/../layout/sidebar.php";?></div>
            <div class="content_section">
                <div id="content_body">

                <div class="main-container">

                    <?php if (!empty($errorMessage)): ?>
                        <p class="status-msg" style="color:#c0392b;"><?php echo htmlspecialchars($errorMessage); ?></p>
                    <?php elseif (!empty($message)): ?>
                        <p class="status-msg"><?php echo htmlspecialchars($message); ?></p>
                    <?php endif; ?>

                    <?php if (!empty($blockedNotices)): ?>
                        <div class="notice-card" style="flex-direction:column; align-items:flex-start;">
                            <span class="notice-icon">⏰</span>
                            <?php foreach ($blockedNotices as $notice): ?>
                                <span><?php echo htmlspecialchars($notice); ?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    
                    <div id="addMealSection" class="tab-content active">
                        <form method="POST" action="">

                            
                            <div class="form-group">
                                <label>মেম্বার সিলেক্ট করুন</label>
                                <div class="custom-select-wrapper">
                                    <?php if ($isManager): ?>
                                        <select name="memberSelect" class="form-control">
                                            <option value="all">For All Members</option>
                                            <?php foreach ($members as $m): ?>
                                                <option value="<?php echo htmlspecialchars($m["userId"]); ?>">
                                                    <?php echo htmlspecialchars($m["Name"]); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    <?php else: ?>
                                        <select name="memberSelect" class="form-control" disabled>
                                            <option value="<?php echo htmlspecialchars($userId); ?>">নিজের জন্য (Only You)</option>
                                        </select>
                                        <input type="hidden" name="memberSelect" value="<?php echo htmlspecialchars($userId); ?>">
                                    <?php endif; ?>
                                </div>
                            </div>

                            
                            <div class="form-group">
                                <label>মিলের তারিখ সিলেক্ট করুন</label>
                                <input type="date" name="mealDate" class="form-control" value="<?php echo htmlspecialchars($mealDate); ?>" required>
                            </div>

                            
                            <div class="section-label-row">
                                <span>মেম্বারদের মিল সংখ্যা সেট করুন</span>
                                <button type="button" class="edit-icon-btn" onclick="openModal()" title="Edit Meal Values">
                                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="#2b2b2b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                                    </svg>
                                </button>
                            </div>

                            
                            <div class="member-card">
                                <?php if (empty($mealsSetForDate)): ?>
                                    <p style="font-size:13px; color:#888;">এই তারিখে এখনো কেউ মিল সেট করেনি।</p>
                                <?php else: ?>
                                    <?php foreach ($mealsSetForDate as $ms): ?>
                                        <div class="card-header" style="border-bottom:1px solid #f0f0f0; padding:10px 0;">
                                            <div class="user-info">
                                                <span class="user-name"><?php echo htmlspecialchars($ms["Name"]); ?></span>
                                                <span style="font-size:11px; color:#999; display:block;">
                                                    সেট: <?php echo date("d M, h:i A", strtotime($ms["updatedAt"] ? $ms["updatedAt"] : $ms["createdAt"])); ?>
                                                </span>
                                            </div>
                                            <span class="total-meals">
                                                B:<?php echo sprintf("%02d", $ms["Morning"]); ?>
                                                L:<?php echo sprintf("%02d", $ms["Lunch"]); ?>
                                                D:<?php echo sprintf("%02d", $ms["Dinner"]); ?>
                                            </span>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>

                            <?php if (!$isManager):
                                list($okM, ) = canSetMeal($mealDate, "Morning", $messRow, $isManager);
                                list($okL, ) = canSetMeal($mealDate, "Lunch", $messRow, $isManager);
                                list($okD, ) = canSetMeal($mealDate, "Dinner", $messRow, $isManager);
                                if(!$okM || !$okL || !$okD): ?>
                                    <p style="font-size:12px; color:#888; margin-top:8px;">
                                        🔒 এই তারিখে <?php
                                            $locked = array();
                                            if(!$okM) $locked[] = "Breakfast";
                                            if(!$okL) $locked[] = "Lunch";
                                            if(!$okD) $locked[] = "Dinner";
                                            echo implode(", ", $locked);
                                        ?> এর কুকিং টাইমের ৩ ঘণ্টার মধ্যে চলে এসেছে, তাই এখন শুধু ম্যানেজার এডিট করতে পারবে।
                                    </p>
                            <?php endif; endif; ?>

                            
                            <input type="hidden" name="breakfast_val" value="<?php echo $breakfast; ?>">
                            <input type="hidden" name="lunch_val" value="<?php echo $lunch; ?>">
                            <input type="hidden" name="dinner_val" value="<?php echo $dinner; ?>">

                            <button type="submit" name="add_meal_submit" class="primary-btn">Add Meal</button>
                        </form>
                    </div>

                </div>

                
                <div id="setMealModal" class="modal-overlay">
                    <div class="modal-card">
                        <h2 class="modal-heading">প্রতিদিনের মিল সেট করে রাখুন মেম্বারদের জন্য।</h2>
                        <p class="modal-desc">মিল তোলার সময় প্রয়োজনে এই সেট করা মিল পরিবর্তন করতে পারবেন।</p>

                        <form method="POST" action="">
                            <div class="modal-input-group toggle-row">
                                <label>Breakfast</label>
                                <label class="switch">
                                    <input type="checkbox" name="modal_breakfast" value="1" <?php echo ($breakfast > 0) ? "checked" : ""; ?>>
                                    <span class="slider round"></span>
                                </label>
                            </div>

                            <div class="modal-input-group toggle-row">
                                <label>Lunch</label>
                                <label class="switch">
                                    <input type="checkbox" name="modal_lunch" value="1" <?php echo ($lunch > 0) ? "checked" : ""; ?>>
                                    <span class="slider round"></span>
                                </label>
                            </div>

                            <div class="modal-input-group toggle-row">
                                <label>Dinner</label>
                                <label class="switch">
                                    <input type="checkbox" name="modal_dinner" value="1" <?php echo ($dinner > 0) ? "checked" : ""; ?>>
                                    <span class="slider round"></span>
                                </label>
                            </div>

                            <div class="modal-btn-row">
                                <button type="button" class="cancel-btn" onclick="closeModal()">Cancel</button>
                                <button type="submit" name="set_meal" class="save-btn">Set Meal</button>
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
        function openModal() {
            document.getElementById('setMealModal').classList.add('active');
        }

        function closeModal() {
            document.getElementById('setMealModal').classList.remove('active');
        }
    </script>
