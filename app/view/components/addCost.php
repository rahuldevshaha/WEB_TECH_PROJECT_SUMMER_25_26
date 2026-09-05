<link rel="stylesheet" href="/app/view/assets/css/addCost.css">
<link rel="stylesheet" href="/app/view/assets/css/home.css">

<div id="home_page">
    <div class="nav">
        <?php include __DIR__ . "/../layout/navbar.php"; ?>
    </div>

    <div class="body_section">
        <div class="sidebar_section">
            <?php include __DIR__ . "/../layout/sidebar.php"; ?>
        </div>

        <div class="content_section">
            <div id="content_body">

                <div class="addCost_page main-container">

                    <p class="page-header-text">মেসের খরচ যুক্ত করুন</p>

                    <?php if (!empty($errorMessage)): ?>
                        <p class="error-msg"><?php echo htmlspecialchars($errorMessage); ?></p>
                    <?php endif; ?>

                    <form method="POST" action="">

                        <div class="form-group">
                            <label>Cost Type</label>
                            <select name="costType" class="form-control" required>
                                <option value="">Select Cost Type</option>

                                <?php foreach ($costTypeOptions as $type): ?>
                                    <option value="<?php echo htmlspecialchars($type); ?>"
                                        <?php echo ($costType === $type) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($type); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Date</label>
                            <input
                                type="date"
                                name="costDate"
                                class="form-control"
                                value="<?php echo htmlspecialchars($costDate); ?>"
                                required
                            >
                        </div>

                        <div class="form-group">
                            <label>Amount</label>
                            <input
                                type="number"
                                step="0.01"
                                min="0.01"
                                name="amount"
                                class="form-control"
                                placeholder="e.g. 1000"
                                value="<?php echo htmlspecialchars($amount); ?>"
                                required
                            >
                        </div>

                        <div class="form-group">
                            <label>Note (optional)</label>
                            <textarea
                                name="note"
                                class="form-control"
                                placeholder="e.g. rice, oil, fish etc."
                            ><?php echo htmlspecialchars($note); ?></textarea>
                        </div>

                        <div class="form-group">
                            <label>Select Shopper</label>
                            <select name="costBy" class="form-control" required>
                                <option value="">Select Shopper</option>

                                <?php foreach ($members as $m): ?>
                                    <option
                                        value="<?php echo htmlspecialchars($m['userId']); ?>"
                                        <?php echo ($costBy == $m['userId']) ? 'selected' : ''; ?>
                                    >
                                        <?php echo htmlspecialchars($m['Name']); ?>
                                        <?php if (isset($m['Role']) && $m['Role'] == 'Manager'): ?>
                                            (Manager)
                                        <?php endif; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="checkbox-row">
                            <input
                                type="checkbox"
                                id="fundCheck"
                                name="auto_fund"
                                <?php echo (!isset($_POST["add_cost_submit"]) || isset($_POST["auto_fund"])) ? '' : ''; ?>
                            >
                            <label for="fundCheck">সমপরিমাণ টাকা তার নামে জমা করুন?</label>
                        </div>

                        <button type="submit" name="add_cost_submit" class="primary-btn">
                            Add Cost
                        </button>
                    </form>

                    <a href="https://youtu.be/4e84b-09E5A" target="_blank" class="yt-banner">
                        <div class="yt-left">
                            <h3>দেখুন কিভাবে<br>মেসের খরচ<br>যুক্ত করবেন</h3>
                            <div class="yt-play-btn">
                                <div class="yt-play-icon"></div>
                            </div>
                        </div>

                        <div class="yt-phone-mockup">
                            <div class="phone-screen-bar"></div>
                            <div class="phone-screen-banner"></div>
                            <div class="phone-screen-card"></div>
                        </div>
                    </a>
                </div>

                <?php if ($showModal): ?>
                    <div id="costSuccessModal" class="modal-overlay active" role="dialog" aria-modal="true">
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

                            <h3 class="modal-title">
                                <span>🍅</span> খরচ যুক্ত হয়েছে
                            </h3>

                            <p class="modal-subtitle">
                                হিসাব চেক করুন মাসের বিস্তারিত হিসাব থেকে
                            </p>

                            <div class="modal-btn-group">
                                <a href="activeMonthDetails.php" class="modal-btn-light">
                                    মাসের বিস্তারিত হিসাব
                                </a>

                                <a href="addcost.php" class="modal-btn-red">
                                    OK, Great!
                                </a>

                            </div>
                        </div>
                    </div>
                <?php endif; ?>

            </div>

            <div class="footer_section">
                <?php include __DIR__ . "/../layout/footer.php"; ?>
            </div>
        </div>
    </div>
</div>

<script src="/app/view/assets/js/addcost.js"></script>
