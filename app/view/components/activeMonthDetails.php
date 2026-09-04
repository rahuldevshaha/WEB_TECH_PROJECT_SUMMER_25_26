<?php
if (!defined('ACTIVE_MONTH_DETAILS_CONTROLLER_LOADED')) {
    require_once __DIR__ . "/../../controller/components/activeMonthDetails.php";
}
?>
<link rel="stylesheet" href="/app/assets/css/activeMonthDetails.css">
<link rel="stylesheet" href="/app/assets/css/home.css">

<div id="home_page">
    <div class="nav"><?php include __DIR__ . "/../layout/navbar.php"; ?></div>
    <div class="body_section">
        <div class="sidebar_section"><?php include __DIR__ . "/../layout/sidebar.php"; ?></div>
        <div class="content_section">
            <div id="content_body">

                <div class="header-bar">
                    <h1 class="page-title">Current Month Details - <?php echo htmlspecialchars($activeMonth); ?></h1>
                </div>

                <div class="tab-nav">
                    <button type="button" class="tab-btn active" onclick="showTab('hisabTab', this)">Hisab</button>
                    <button type="button" class="tab-btn" onclick="showTab('mealTab', this)">Meal</button>
                    <button type="button" class="tab-btn" onclick="showTab('depositTab', this)">Deposit</button>
                    <button type="button" class="tab-btn" onclick="showTab('mealCostTab', this)">Meal Cost</button>
                    <button type="button" class="tab-btn" onclick="showTab('otherCostTab', this)">Other Cost</button>
                    <button type="button" class="tab-btn" onclick="showTab('bazarTab', this)">Bazar</button>
                </div>

                <!-- =====================================================
                     HISAB - READ ONLY
                ====================================================== -->
                <div id="hisabTab" class="tab-pane active">
                    <div class="meal-table-container">
                        <table class="meal-table">
                            <thead>
                                <tr>
                                    <th>Member</th>
                                    <th>Total Meal</th>
                                    <th>Meal Cost</th>
                                    <th>Total Deposit</th>
                                    <th>Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>Total</strong></td>
                                    <td><strong><?php echo number_format($hisabTotalMeal, 2); ?></strong></td>
                                    <td><strong><?php echo number_format($hisabTotalCost, 2); ?></strong></td>
                                    <td><strong><?php echo number_format($hisabTotalDeposit, 2); ?></strong></td>
                                    <td><strong><?php echo number_format($hisabTotalDeposit - $hisabTotalCost, 2); ?></strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="meal-table-container">
                        <table class="meal-table">
                            <thead>
                                <tr>
                                    <th>Member</th>
                                    <th>Meal</th>
                                    <th>Cost</th>
                                    <th>Paid</th>
                                    <th>Hisab</th>
                                </tr>
                            </thead>
                            <tbody>
<?php foreach($members as $member): ?>
<?php
$memberId = $member["userId"];
$memberMeal = isset($hisabMemberMeals[$memberId]) ? $hisabMemberMeals[$memberId] : 0;
$memberCost = $memberMeal * $hisabMealRate;
$memberPaid = isset($hisabMemberDeposits[$memberId]) ? $hisabMemberDeposits[$memberId] : 0;
$memberBalance = $memberPaid - $memberCost;
?>
                                <tr>
                                    <td><?php echo htmlspecialchars($member["Name"]); ?></td>
                                    <td><?php echo number_format($memberMeal, 2); ?></td>
                                    <td><?php echo number_format($memberCost, 2); ?></td>
                                    <td><?php echo number_format($memberPaid, 2); ?></td>
                                    <td>
<?php if($memberBalance < 0): ?>
                                        <strong>দেবে <?php echo number_format(abs($memberBalance), 2); ?></strong>
<?php elseif($memberBalance > 0): ?>
                                        <strong>পাবে <?php echo number_format($memberBalance, 2); ?></strong>
<?php else: ?>
                                        <strong>সমান</strong>
<?php endif; ?>
                                    </td>
                                </tr>
<?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="meal-table-container">
                        <table class="meal-table">
                            <tbody>
                                <tr><th>Meal Rate</th><td><strong><?php echo number_format($hisabMealRate, 2); ?></strong></td></tr>
                                <tr><th>Period</th><td><?php echo date("d M Y", strtotime($monthStart)); ?> - <?php echo date("d M Y"); ?></td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- =====================================================
                     MEAL - VERTICAL LAYOUT
                ====================================================== -->
                <div id="mealTab" class="tab-pane">
                    <div class="meal-vertical-container">

<?php if(empty($mealRecords)): ?>
                        <div class="empty-state-box">
                            <p class="empty-state-text">No meal has been added in this month!</p>
                        </div>
<?php else: ?>

<?php
$mealByDate = array();

foreach($mealRecords as $record){
    $mealByDate[$record["mealDate"]][$record["userId"]] = $record;
}
?>

<?php foreach($mealByDate as $mealDate => $dateRecords): ?>
<?php
$totalMorning = 0;
$totalLunch = 0;
$totalDinner = 0;

foreach($dateRecords as $dateRecord){
    $totalMorning += floatval($dateRecord["Morning"]);
    $totalLunch += floatval($dateRecord["Lunch"]);
    $totalDinner += floatval($dateRecord["Dinner"]);
}

$totalMeal = $totalMorning + $totalLunch + $totalDinner;
?>

                        <div class="meal-date-card">

                            <div class="meal-date-header">
                                <span class="meal-date-label">Date</span>
                                <strong><?php echo date("d M Y", strtotime($mealDate)); ?></strong>
                            </div>

                            <div class="meal-total-card">
                                <div class="meal-total-item">
                                    <span>Breakfast</span>
                                    <strong><?php echo number_format($totalMorning, 2); ?></strong>
                                </div>

                                <div class="meal-total-item">
                                    <span>Lunch</span>
                                    <strong><?php echo number_format($totalLunch, 2); ?></strong>
                                </div>

                                <div class="meal-total-item">
                                    <span>Dinner</span>
                                    <strong><?php echo number_format($totalDinner, 2); ?></strong>
                                </div>

                                <div class="meal-total-item meal-grand-total">
                                    <span>Total Meal</span>
                                    <strong><?php echo number_format($totalMeal, 2); ?></strong>
                                </div>
                            </div>

                            <div class="meal-member-list">

<?php foreach($members as $member): ?>
<?php
$record = isset($dateRecords[$member["userId"]])
    ? $dateRecords[$member["userId"]]
    : null;

$morning = $record ? floatval($record["Morning"]) : 0;
$lunch = $record ? floatval($record["Lunch"]) : 0;
$dinner = $record ? floatval($record["Dinner"]) : 0;

$memberTotal = $morning + $lunch + $dinner;
?>

                                <div class="meal-member-card">

                                    <div class="meal-member-name">
                                        <?php echo htmlspecialchars($member["Name"]); ?>
                                    </div>

                                    <div class="meal-values">

                                        <div class="meal-value">
                                            <span>Breakfast</span>
                                            <strong><?php echo number_format($morning, 2); ?></strong>
                                        </div>

                                        <div class="meal-value">
                                            <span>Lunch</span>
                                            <strong><?php echo number_format($lunch, 2); ?></strong>
                                        </div>

                                        <div class="meal-value">
                                            <span>Dinner</span>
                                            <strong><?php echo number_format($dinner, 2); ?></strong>
                                        </div>

                                        <div class="meal-value total">
                                            <span>Total</span>
                                            <strong><?php echo number_format($memberTotal, 2); ?></strong>
                                        </div>

                                    </div>

<?php if($isManager && $record): ?>
                                    <button type="button"
                                            class="edit-icon-btn meal-edit-btn"
                                            onclick='openMealEditModal(<?php echo json_encode($record["mealRecordId"]); ?>, <?php echo json_encode($record["mealDate"]); ?>, <?php echo json_encode((int)$record["Morning"]); ?>, <?php echo json_encode((int)$record["Lunch"]); ?>, <?php echo json_encode((int)$record["Dinner"]); ?>)'
                                            title="Edit Meal">✎</button>
<?php endif; ?>

                                </div>

<?php endforeach; ?>

                            </div>
                        </div>

<?php endforeach; ?>
<?php endif; ?>

                    </div>
                </div>

                <!-- =====================================================
                     DEPOSIT
                ====================================================== -->
                <div id="depositTab" class="tab-pane">
<?php if(empty($depositRecords)): ?>
                    <div class="empty-state-box">
                        <p class="empty-state-text">No deposit has been added in this month!</p>
<?php if($isManager): ?>
                        <a href="addDeposit.php" class="primary-action-btn">Add Deposit</a>
<?php endif; ?>
                    </div>
<?php else: ?>
                    <div class="cost-card-list">
<?php foreach($depositRecords as $deposit): ?>
                        <div class="cost-item-card">
                            <div class="cost-card-left">
                                <div class="date-badge">
                                    <span class="day"><?php echo date("d", strtotime($deposit["submitDate"])); ?></span>
                                    <span class="month"><?php echo date("M", strtotime($deposit["submitDate"])); ?></span>
                                </div>
                                <div class="cost-info">
                                    <strong>Deposited By: <?php echo htmlspecialchars($deposit["memberName"]); ?></strong><br>
                                    <strong>Deposit Amount: <?php echo number_format(floatval($deposit["amount"]), 2); ?></strong><br>
                                    Deposit Note: <?php echo htmlspecialchars($deposit["note"] ?? ""); ?><br>
                                    Received By Manager: <?php echo !empty($deposit["receivedByName"]) ? htmlspecialchars($deposit["receivedByName"]) : "N/A"; ?><br>
                                    Date: <?php echo date("d M Y", strtotime($deposit["submitDate"])); ?>
                                </div>
<?php if($isManager): ?>
                                <button type="button" class="edit-icon-btn cost-edit-btn"
                                    onclick='openDepositEditModal(<?php echo json_encode($deposit["fundId"]); ?>, <?php echo json_encode($deposit["submittedBy"]); ?>, <?php echo json_encode($deposit["amount"]); ?>, <?php echo json_encode($deposit["note"] ?? ""); ?>, <?php echo json_encode($deposit["submitDate"]); ?>)'
                                    title="Edit Deposit">✎</button>
<?php endif; ?>
                            </div>
                        </div>
<?php endforeach; ?>
                    </div>
<?php endif; ?>
                </div>

                <!-- =====================================================
                     MEAL COST
                ====================================================== -->
                <div id="mealCostTab" class="tab-pane">
<?php if(empty($mealCostRecords)): ?>
                    <div class="empty-state-box"><p class="empty-state-text">No meal cost has been added in this month!</p></div>
<?php else: ?>
                    <div class="cost-card-list">
<?php foreach($mealCostRecords as $cost): ?>
                        <div class="cost-item-card">
                            <div class="cost-card-left">
                                <div class="date-badge">
                                    <span class="day"><?php echo date("d", strtotime($cost["costDate"])); ?></span>
                                    <span class="month"><?php echo date("M", strtotime($cost["costDate"])); ?></span>
                                </div>
                                <div class="cost-info">
                                    <strong>Cost Amount: <?php echo number_format(floatval($cost["amount"]), 2); ?></strong><br>
                                    Bazar List: <?php echo htmlspecialchars($cost["note"] ?? ""); ?><br>
                                    Shopper: <?php echo htmlspecialchars($cost["memberName"]); ?><br>
                                    Assign By Manager: <?php echo !empty($cost["assignByName"]) ? htmlspecialchars($cost["assignByName"]) : "N/A"; ?><br>
                                    Date: <?php echo date("d M Y", strtotime($cost["costDate"])); ?>
                                </div>
<?php if($isManager): ?>
                                <button type="button" class="edit-icon-btn cost-edit-btn"
                                    onclick='openCostEditModal(<?php echo json_encode($cost["expenseId"]); ?>, <?php echo json_encode($cost["costType"]); ?>, <?php echo json_encode($cost["costBy"]); ?>, <?php echo json_encode($cost["amount"]); ?>, <?php echo json_encode($cost["note"] ?? ""); ?>, <?php echo json_encode($cost["costDate"]); ?>)'
                                    title="Edit Meal Cost">✎</button>
<?php endif; ?>
                            </div>
                        </div>
<?php endforeach; ?>
                    </div>
<?php endif; ?>
                </div>

                <!-- =====================================================
                     OTHER COST
                ====================================================== -->
                <div id="otherCostTab" class="tab-pane">
<?php if(empty($otherCostRecords)): ?>
                    <div class="empty-state-box">
                        <p class="empty-state-text">No other cost has been added in this month!</p>
<?php if($isManager): ?>
                        <a href="addCost.php" class="primary-action-btn">Add Cost</a>
<?php endif; ?>
                    </div>
<?php else: ?>
<?php
// Other Cost tab excludes Meal Cost because Meal Cost has its own tab.
$otherCostTypes = array("Gas Bill", "Electricity Bill", "WiFi Bill", "Other");
?>
                    <div class="other-cost-filter">
                        <label for="otherCostTypeFilter">Cost Type</label>
                        <select id="otherCostTypeFilter" onchange="filterOtherCosts(this.value)">
                            <option value="all">All Cost Types</option>
<?php foreach($otherCostTypes as $costType): ?>
                            <option value="<?php echo htmlspecialchars($costType, ENT_QUOTES, 'UTF-8'); ?>">
                                <?php echo htmlspecialchars($costType); ?>
                            </option>
<?php endforeach; ?>
                        </select>
                    </div>

                    <div class="cost-card-list">
<?php foreach($otherCostRecords as $cost): ?>
                        <div class="cost-item-card other-cost-item"
                             data-cost-type="<?php echo htmlspecialchars($cost["costType"], ENT_QUOTES, 'UTF-8'); ?>">
                            <div class="cost-card-left">
                                <div class="date-badge">
                                    <span class="day"><?php echo date("d", strtotime($cost["costDate"])); ?></span>
                                    <span class="month"><?php echo date("M", strtotime($cost["costDate"])); ?></span>
                                </div>
                                <div class="cost-info">
                                    <strong>Cost Type: <?php echo htmlspecialchars($cost["costType"]); ?></strong><br>
                                    Cost Amount: <?php echo number_format(floatval($cost["amount"]), 2); ?><br>
                                    Note: <?php echo htmlspecialchars($cost["note"] ?? ""); ?><br>
                                    Expense By: <?php echo htmlspecialchars($cost["memberName"]); ?><br>
                                    Assign By Manager: <?php echo !empty($cost["assignByName"]) ? htmlspecialchars($cost["assignByName"]) : "N/A"; ?><br>
                                    Date: <?php echo date("d M Y", strtotime($cost["costDate"])); ?>
                                </div>
<?php if($isManager): ?>
                                <button type="button" class="edit-icon-btn cost-edit-btn"
                                    onclick='openCostEditModal(<?php echo json_encode($cost["expenseId"]); ?>, <?php echo json_encode($cost["costType"]); ?>, <?php echo json_encode($cost["costBy"]); ?>, <?php echo json_encode($cost["amount"]); ?>, <?php echo json_encode($cost["note"] ?? ""); ?>, <?php echo json_encode($cost["costDate"]); ?>)'
                                    title="Edit Other Cost">✎</button>
<?php endif; ?>
                            </div>
                        </div>
<?php endforeach; ?>
                    </div>
                    <div id="otherCostFilterEmpty" class="empty-state-box" style="display:none;">
                        <p class="empty-state-text">No cost found for this cost type.</p>
                    </div>
<?php endif; ?>
                </div>

                <!-- =====================================================
                     BAZAR - MEMBER WISE
                ====================================================== -->
                <div id="bazarTab" class="tab-pane">
                    <div class="meal-table-container">
                        <table class="meal-table">
                            <thead>
                                <tr>
                                    <th>Member</th>
                                    <th>Total Bazar Days</th>
                                    <th>Bazar Dates</th>
                                </tr>
                            </thead>
                            <tbody>
<?php if(empty($bazarMemberRecords)): ?>
                                <tr><td colspan="3">No bazar assignment found!</td></tr>
<?php else: ?>
<?php foreach($bazarMemberRecords as $bazarMember): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($bazarMember["Name"]); ?></strong></td>
                                    <td><?php echo $bazarMember["bazarCount"]; ?></td>
                                    <td>
<?php if(empty($bazarMember["bazarDates"])): ?>
                                        No Bazar Date
<?php else: ?>
<?php foreach($bazarMember["bazarDates"] as $index => $bazarDate): ?>
                                        <?php echo date("d M", strtotime($bazarDate)); ?><?php echo $index < count($bazarMember["bazarDates"]) - 1 ? ", " : ""; ?>
<?php endforeach; ?>
<?php endif; ?>
                                    </td>
                                </tr>
<?php endforeach; ?>
<?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            <div class="footer_section"><?php include __DIR__ . "/../layout/footer.php"; ?></div>
        </div>
    </div>
</div>

<!-- =====================================================
     MEAL EDIT MODAL
====================================================== -->
<div id="mealEditModal" class="modal-overlay">
    <div class="modal-card">
        <h2 class="modal-title">Edit Meal</h2>
        <form method="POST">
            <input type="hidden" name="edit_meal_submit" value="1">
            <input type="hidden" name="mealRecordId" id="editMealRecordId">

            <div class="modal-form-group">
                <label>Date</label>
                <input type="date" name="mealDate" id="editMealDate" class="modal-input" required>
            </div>

            <div class="meal-toggle-list">
                <label class="meal-toggle-row">
                    <span>Morning</span>
                    <input type="checkbox" name="morning" id="editMealMorning" value="1">
                    <span class="toggle-slider"></span>
                </label>
                <label class="meal-toggle-row">
                    <span>Lunch</span>
                    <input type="checkbox" name="lunch" id="editMealLunch" value="1">
                    <span class="toggle-slider"></span>
                </label>
                <label class="meal-toggle-row">
                    <span>Dinner</span>
                    <input type="checkbox" name="dinner" id="editMealDinner" value="1">
                    <span class="toggle-slider"></span>
                </label>
            </div>

            <div class="modal-btn-row">
                <button type="button" class="btn-cancel" onclick="closeMealEditModal()">Cancel</button>
                <button type="submit" class="btn-black">Update</button>
            </div>
        </form>
    </div>
</div>

<!-- =====================================================
     DEPOSIT EDIT MODAL
====================================================== -->
<div id="depositEditModal" class="modal-overlay">
    <div class="modal-card">
        <h2 class="modal-title">Edit Deposit</h2>
        <form method="POST">
            <input type="hidden" name="edit_deposit_submit" value="1">
            <input type="hidden" name="fundId" id="editFundId">

            <div class="modal-form-group">
                <label>Member</label>
                <select name="submittedBy" id="editDepositMember" class="modal-input" required>
<?php foreach($members as $member): ?>
                    <option value="<?php echo htmlspecialchars($member["userId"]); ?>"><?php echo htmlspecialchars($member["Name"]); ?></option>
<?php endforeach; ?>
                </select>
            </div>

            <div class="modal-form-group">
                <label>Amount</label>
                <input type="number" step="0.01" min="0" name="amount" id="editDepositAmount" class="modal-input" required>
            </div>

            <div class="modal-form-group">
                <label>Note</label>
                <textarea name="note" id="editDepositNote" class="modal-input"></textarea>
            </div>

            <div class="modal-form-group">
                <label>Date</label>
                <input type="date" name="submitDate" id="editDepositDate" class="modal-input" required>
            </div>

            <div class="modal-btn-row modal-btn-delete-row">
                <button type="button" class="btn-delete" onclick="openDeleteConfirm('deposit')">Delete</button>
                <span class="modal-btn-spacer"></span>
                <button type="button" class="btn-cancel" onclick="closeDepositEditModal()">Cancel</button>
                <button type="submit" class="btn-black">Update</button>
            </div>
        </form>
    </div>
</div>

<!-- =====================================================
     COST EDIT MODAL
====================================================== -->
<div id="costEditModal" class="modal-overlay">
    <div class="modal-card">
        <h2 class="modal-title">Edit Cost</h2>
        <form method="POST">
            <input type="hidden" name="edit_cost_submit" value="1">
            <input type="hidden" name="expenseId" id="editExpenseId">

            <div class="modal-form-group">
                <label>Cost Type</label>
                <select name="costType" id="editCostType" class="modal-input" required>
                    <option value="Meal Cost">Meal Cost</option>
                    <option value="Other Cost">Other Cost</option>
                </select>
            </div>

            <div class="modal-form-group">
                <label>Member</label>
                <select name="costBy" id="editCostBy" class="modal-input" required>
<?php foreach($members as $member): ?>
                    <option value="<?php echo htmlspecialchars($member["userId"]); ?>"><?php echo htmlspecialchars($member["Name"]); ?></option>
<?php endforeach; ?>
                </select>
            </div>

            <div class="modal-form-group">
                <label>Amount</label>
                <input type="number" step="0.01" min="0" name="amount" id="editCostAmount" class="modal-input" required>
            </div>

            <div class="modal-form-group">
                <label>Note</label>
                <textarea name="note" id="editCostNote" class="modal-input"></textarea>
            </div>

            <div class="modal-form-group">
                <label>Date</label>
                <input type="date" name="costDate" id="editCostDate" class="modal-input" required>
            </div>

            <div class="modal-btn-row modal-btn-delete-row">
                <button type="button" class="btn-delete" onclick="openDeleteConfirm('cost')">Delete</button>
                <span class="modal-btn-spacer"></span>
                <button type="button" class="btn-cancel" onclick="closeCostEditModal()">Cancel</button>
                <button type="submit" class="btn-black">Update</button>
            </div>
        </form>
    </div>
</div>

<!-- =====================================================
     DELETE CONFIRM MODAL
====================================================== -->
<div id="deleteConfirmModal" class="modal-overlay">
    <div class="modal-card delete-confirm-card">
        <h2 class="modal-title">Confirm Delete</h2>
        <p class="delete-confirm-text">Are you sure you want to delete this record?</p>

        <form method="POST" id="deleteDepositForm">
            <input type="hidden" name="delete_deposit_submit" value="1">
            <input type="hidden" name="fundId" id="deleteFundId">
        </form>

        <form method="POST" id="deleteCostForm">
            <input type="hidden" name="delete_cost_submit" value="1">
            <input type="hidden" name="expenseId" id="deleteExpenseId">
        </form>

        <div class="modal-btn-row">
            <button type="button" class="btn-cancel" onclick="closeDeleteConfirm()">Cancel</button>
            <button type="button" class="btn-delete-confirm" onclick="submitDelete()">Delete</button>
        </div>
    </div>
</div>

<script src="/app/assets/js/activeMonthDetails.js"></script>
