<link rel="stylesheet" href="/app/assets/css/home.css">
<link rel="stylesheet" href="/app/assets/css/history.css">

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

                <div class="history-container">
                    <div class="history-header">
                        <div>
                            <h1>Mess History</h1>
                            <p><?php echo htmlspecialchars($messName); ?> &bull; Previous month records</p>
                        </div>
                        <div class="history-count">
                            <span><?php echo count($historyRecords); ?></span>
                            <small>Months</small>
                        </div>
                    </div>

                    <?php if (!empty($historyRecords)): ?>
                    <div class="history-toolbar">
                        <div class="search-box">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <circle cx="11" cy="11" r="7"></circle>
                                <line x1="16.5" y1="16.5" x2="21" y2="21"></line>
                            </svg>
                            <input type="text" id="historySearch" placeholder="Search month..." autocomplete="off">
                        </div>

                        <select id="historySort" class="sort-select" aria-label="Sort history">
                            <option value="newest">Newest first</option>
                            <option value="oldest">Oldest first</option>
                            <option value="expense-high">Highest expense</option>
                            <option value="meal-high">Highest meal</option>
                        </select>
                    </div>

                    <div class="history-grid" id="historyGrid">
                        <?php foreach ($historyRecords as $index => $history): ?>
                            <?php
                                $expense = (float)$history['totalExpense'];
                                $meal = (float)$history['totalMeal'];
                                $rate = (float)$history['mealRate'];
                                $fund = (float)$history['totalFund'];
                                $due = (float)$history['totalDue'];
                                $member = (int)$history['totalMember'];
                            ?>
                            <article class="history-card"
                                data-month="<?php echo htmlspecialchars(strtolower($history['month'])); ?>"
                                data-index="<?php echo $index; ?>"
                                data-expense="<?php echo $expense; ?>"
                                data-meal="<?php echo $meal; ?>">

                                <div class="card-top">
                                    <div class="month-icon">
                                        <svg viewBox="0 0 24 24" aria-hidden="true">
                                            <rect x="3" y="4" width="18" height="17" rx="2"></rect>
                                            <line x1="8" y1="2" x2="8" y2="6"></line>
                                            <line x1="16" y1="2" x2="16" y2="6"></line>
                                            <line x1="3" y1="10" x2="21" y2="10"></line>
                                        </svg>
                                    </div>
                                    <div class="month-title">
                                        <h2><?php echo htmlspecialchars($history['month']); ?></h2>
                                        <span><?php echo $member; ?> member<?php echo $member !== 1 ? 's' : ''; ?></span>
                                    </div>
                                    <button class="view-btn" type="button" onclick="openHistoryModal(this)" title="View details">
                                        View
                                    </button>
                                </div>

                                <div class="stats-grid">
                                    <div class="stat-item">
                                        <span>Total Meal</span>
                                        <strong><?php echo number_format($meal, 2); ?></strong>
                                    </div>
                                    <div class="stat-item">
                                        <span>Meal Rate</span>
                                        <strong><?php echo htmlspecialchars($currency); ?> <?php echo number_format($rate, 2); ?></strong>
                                    </div>
                                    <div class="stat-item">
                                        <span>Total Expense</span>
                                        <strong><?php echo htmlspecialchars($currency); ?> <?php echo number_format($expense, 2); ?></strong>
                                    </div>
                                    <div class="stat-item">
                                        <span>Total Fund</span>
                                        <strong><?php echo htmlspecialchars($currency); ?> <?php echo number_format($fund, 2); ?></strong>
                                    </div>
                                </div>

                                <div class="card-footer">
                                    <span>Due</span>
                                    <strong class="<?php echo $due > 0 ? 'due-positive' : ($due < 0 ? 'due-negative' : 'due-zero'); ?>">
                                        <?php echo htmlspecialchars($currency); ?> <?php echo number_format($due, 2); ?>
                                    </strong>
                                </div>

                                <div class="history-data" hidden
                                    data-month="<?php echo htmlspecialchars($history['month']); ?>"
                                    data-member="<?php echo $member; ?>"
                                    data-meal="<?php echo number_format($meal, 2); ?>"
                                    data-expense="<?php echo number_format($expense, 2); ?>"
                                    data-rate="<?php echo number_format($rate, 2); ?>"
                                    data-fund="<?php echo number_format($fund, 2); ?>"
                                    data-due="<?php echo number_format($due, 2); ?>"
                                    data-currency="<?php echo htmlspecialchars($currency); ?>"
                                    data-created="<?php echo htmlspecialchars($history['createdAt']); ?>">
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>

                    <div id="noSearchResult" class="no-search-result" hidden>
                        <div class="empty-icon">⌕</div>
                        <h3>No matching history</h3>
                        <p>Try another month name.</p>
                    </div>

                    <?php else: ?>
                        <div class="empty-history">
                            <div class="empty-icon">
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <rect x="4" y="3" width="16" height="18" rx="2"></rect>
                                    <line x1="8" y1="8" x2="16" y2="8"></line>
                                    <line x1="8" y1="12" x2="16" y2="12"></line>
                                    <line x1="8" y1="16" x2="13" y2="16"></line>
                                </svg>
                            </div>
                            <h2>No History Found</h2>
                            <p>There is no previous month history available yet.</p>
                        </div>
                    <?php endif; ?>
                </div>

            </div>

            <div class="footer_section">
                <?php include __DIR__ . "/../layout/footer.php"; ?>
            </div>
        </div>
    </div>
</div>

<div class="history-modal" id="historyModal" aria-hidden="true">
    <div class="history-modal-backdrop" onclick="closeHistoryModal()"></div>
    <div class="history-modal-card" role="dialog" aria-modal="true" aria-labelledby="modalMonth">
        <button type="button" class="modal-close" onclick="closeHistoryModal()" aria-label="Close">&times;</button>
        <div class="modal-heading">
            <div class="modal-calendar">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <rect x="3" y="4" width="18" height="17" rx="2"></rect>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
            </div>
            <div>
                <h2 id="modalMonth">Month</h2>
                <p id="modalCreated">History summary</p>
            </div>
        </div>

        <div class="modal-stats">
            <div><span>Total Member</span><strong id="modalMember">0</strong></div>
            <div><span>Total Meal</span><strong id="modalMeal">0</strong></div>
            <div><span>Meal Rate</span><strong id="modalRate">0</strong></div>
            <div><span>Total Expense</span><strong id="modalExpense">0</strong></div>
            <div><span>Total Fund</span><strong id="modalFund">0</strong></div>
            <div><span>Total Due</span><strong id="modalDue">0</strong></div>
        </div>

        <button type="button" class="modal-done" onclick="closeHistoryModal()">Close</button>
    </div>
</div>

<script src="/app/assets/js/history.js"></script>
