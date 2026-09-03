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
            <div class="sidebar_section"> <?php include __DIR__ . "/../layout/sidebar.php"; ?></div>
            <div class="content_section">
                <div id="content_body">


                    
                    <div class="header-bar">
                        <h1 class="page-title">Current Month Details - <?php echo htmlspecialchars($activeMonth); ?></h1>
                        <button type="button" class="download-pdf-btn" onclick="window.print()">Download PDF</button>
                    </div>

                    
                    <div class="tab-nav">
                        <button type="button" class="tab-btn active" onclick="showTab('hisabTab', this)">Hisab</button>
                        <button type="button" class="tab-btn" onclick="showTab('mealTab', this)">Meal</button>
                        <button type="button" class="tab-btn" onclick="showTab('depositTab', this)">Deposit</button>
                        <button type="button" class="tab-btn" onclick="showTab('mealCostTab', this)">Meal Cost</button>
                    </div>

                    
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
                                    <tr>
                                        <th>Meal Rate</th>
                                        <td><strong><?php echo number_format($hisabMealRate, 2); ?></strong></td>
                                    </tr>
                                    <tr>
                                        <th>Period</th>
                                        <td><?php echo date("d M Y", strtotime($monthStart)); ?> - <?php echo date("d M Y"); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    
                    <div id="mealTab" class="tab-pane active">
                        <div class="meal-table-container">
                            <table class="meal-table">
                                <thead>
                                    <tr>
                                        <th style="width: 15%;">Date</th>
                                        <th style="width: 25%;">সর্বমোট মিল</th>
<?php foreach($members as $member): ?>
                                        <th><?php echo htmlspecialchars($member["Name"]); ?></th>
<?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody>
<?php if(empty($mealRecords)): ?>
                                    <tr>
                                        <td colspan="<?php echo 2 + count($members); ?>">No meal has been added in this month!</td>
                                    </tr>
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
                                    <tr>
                                        <td><?php echo date("d M", strtotime($mealDate)); ?></td>
                                        <td>
                                            Breakfast : <?php echo number_format($totalMorning, 2); ?><br>
                                            Lunch: <?php echo number_format($totalLunch, 2); ?><br>
                                            Dinner: <?php echo number_format($totalDinner, 2); ?><br>
                                            <strong>Total: <?php echo number_format($totalMeal, 2); ?></strong>
                                        </td>
<?php foreach($members as $member): ?>
<?php
$record = isset($dateRecords[$member["userId"]]) ? $dateRecords[$member["userId"]] : null;
$morning = $record ? floatval($record["Morning"]) : 0;
$lunch = $record ? floatval($record["Lunch"]) : 0;
$dinner = $record ? floatval($record["Dinner"]) : 0;
$memberTotal = $morning + $lunch + $dinner;
?>
                                        <td class="member-meal-cell">
                                            Breakfast : <?php echo number_format($morning, 2); ?><br>
                                            Lunch: <?php echo number_format($lunch, 2); ?><br>
                                            Dinner: <?php echo number_format($dinner, 2); ?><br>
                                            <strong>Total: <?php echo number_format($memberTotal, 2); ?></strong>
                                        </td>
<?php endforeach; ?>
                                    </tr>
<?php endforeach; ?>
<?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    
                    <div id="depositTab" class="tab-pane">
<?php if(empty($depositRecords)): ?>
                        <div class="empty-state-box">
                            <p class="empty-state-text">No deposit has been added in this month!</p>
                            <a href="addDeposit.php" class="primary-action-btn">Add Deposit</a>
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
                                        <strong>Name: <?php echo htmlspecialchars($deposit["memberName"]); ?></strong><br>
                                        <strong>Deposit Amount: <?php echo number_format(floatval($deposit["amount"]), 2); ?></strong><br>
                                        Deposit Note: <?php echo htmlspecialchars($deposit["note"] ?? ""); ?><br>
                                        Date: <?php echo date("d M Y", strtotime($deposit["submitDate"])); ?>
                                    </div>
                                </div>
                            </div>
<?php endforeach; ?>
                        </div>
<?php endif; ?>
                    </div>

                    
                    <div id="mealCostTab" class="tab-pane">
<?php if(empty($mealCostRecords)): ?>
                        <div class="empty-state-box">
                            <p class="empty-state-text">No meal cost has been added in this month!</p>
                        </div>
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
                                        Shoppers: <?php echo htmlspecialchars($cost["memberName"]); ?><br>
                                        Date: <?php echo date("d M Y", strtotime($cost["costDate"])); ?>
                                    </div>
                                </div>
                            </div>
<?php endforeach; ?>
                        </div>
<?php endif; ?>
                    </div>

                </div>
                <div class="footer_section"><?php include __DIR__ . "/../layout/footer.php"; ?></div>
            </div>
        </div>
    </div>







    <script src="/app/assets/js/activeMonthDetails.js"></script>
