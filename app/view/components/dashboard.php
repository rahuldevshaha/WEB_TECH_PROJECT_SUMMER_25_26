<?php



ProtectedRequest("../login/socialLogin.php");

$userId = getSessionValue("userId");
$messId = getSessionValue("messId");

if(empty($messId)){
    $sqlQ = "SELECT messId FROM Member WHERE userId='" . addslashes($userId) . "' LIMIT 1";
    $result = exeQuery($sqlQ);
    if($result && getRowCount($result) > 0){
        $row = getDataRow($result);
        $messId = $row["messId"];
        setSessionValue("messId", $messId);
    }
}

$today = date("Y-m-d");
$monthStart = date("Y-m-01");
$monthEnd = date("Y-m-t");
$displayMonth = date("F Y");




$mess = array("messName" => "My Mess", "Currency" => "BDT");
$sqlQ = "SELECT messName, Currency FROM Messes WHERE messId='" . addslashes($messId) . "' LIMIT 1";
$result = exeQuery($sqlQ);
if($result && getRowCount($result) > 0){
    $mess = getDataRow($result);
}
$currency = !empty($mess["Currency"]) ? $mess["Currency"] : "BDT";




$members = array();
$mealByUser = array();
$depositByUser = array();

$sqlQ = "SELECT u.userId, u.Name, u.Avater, m.Role
         FROM Member m
         INNER JOIN Users u ON u.userId=m.userId
         WHERE m.messId='" . addslashes($messId) . "'
         ORDER BY CASE WHEN m.Role='Manager' THEN 0 ELSE 1 END, u.Name ASC";
$result = exeQuery($sqlQ);
if($result){
    while($row = getDataRow($result)){
        $members[] = $row;
        $mealByUser[$row["userId"]] = 0;
        $depositByUser[$row["userId"]] = 0;
    }
}




$totalMeal = 0;
$sqlQ = "SELECT userId, Morning, Lunch, Dinner
         FROM MealRecord
         WHERE messId='" . addslashes($messId) . "'
         AND mealDate BETWEEN '$monthStart' AND '$today'";
$result = exeQuery($sqlQ);
if($result){
    while($row = getDataRow($result)){
        $meal = floatval($row["Morning"]) + floatval($row["Lunch"]) + floatval($row["Dinner"]);
        $totalMeal += $meal;
        if(isset($mealByUser[$row["userId"]])){
            $mealByUser[$row["userId"]] += $meal;
        }
    }
}




$totalDeposit = 0;
$sqlQ = "SELECT submittedBy, amount
         FROM Funds
         WHERE messId='" . addslashes($messId) . "'
         AND submitDate BETWEEN '$monthStart' AND '$today'";
$result = exeQuery($sqlQ);
if($result){
    while($row = getDataRow($result)){
        $amount = floatval($row["amount"]);
        $totalDeposit += $amount;
        if(isset($depositByUser[$row["submittedBy"]])){
            $depositByUser[$row["submittedBy"]] += $amount;
        }
    }
}




$totalMealCost = 0;
$totalOtherCost = 0;
$sqlQ = "SELECT amount, costType
         FROM Expenses
         WHERE messId='" . addslashes($messId) . "'
         AND costDate BETWEEN '$monthStart' AND '$today'";
$result = exeQuery($sqlQ);
if($result){
    while($row = getDataRow($result)){
        $amount = floatval($row["amount"]);
        if($row["costType"] == "Meal Cost"){
            $totalMealCost += $amount;
        }else{
            $totalOtherCost += $amount;
        }
    }
}

$totalCost = $totalMealCost + $totalOtherCost;
$mealRate = $totalMeal > 0 ? ($totalMealCost / $totalMeal) : 0;




$myMeal = isset($mealByUser[$userId]) ? $mealByUser[$userId] : 0;
$myDeposit = isset($depositByUser[$userId]) ? $depositByUser[$userId] : 0;
$myMealCost = $myMeal * $mealRate;
$myBalance = $myDeposit - $myMealCost;




$myBazarDates = array();
$sqlQ = "SELECT bazarDates
         FROM AssignBazar
         WHERE messId='" . addslashes($messId) . "'
         AND userId='" . addslashes($userId) . "'
         ORDER BY createdAt DESC
         LIMIT 1";
$result = exeQuery($sqlQ);

if($result && getRowCount($result) > 0){
    $row = getDataRow($result);
    $decoded = json_decode($row["bazarDates"], true);

    if(is_array($decoded)){
        foreach($decoded as $dateValue){
            $dateValue = trim((string)$dateValue);
            if(preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateValue)){
                $myBazarDates[] = $dateValue;
            }
        }
    }
}

$myBazarDates = array_values(array_unique($myBazarDates));
sort($myBazarDates);

$upcomingBazarDates = array();
$completedBazarDates = array();
foreach($myBazarDates as $bazarDate){
    if($bazarDate >= $today){
        $upcomingBazarDates[] = $bazarDate;
    }else{
        $completedBazarDates[] = $bazarDate;
    }
}




$memberDashboard = array();
foreach($members as $member){
    $memberId = $member["userId"];
    $memberMeal = isset($mealByUser[$memberId]) ? $mealByUser[$memberId] : 0;
    $memberDeposit = isset($depositByUser[$memberId]) ? $depositByUser[$memberId] : 0;
    $memberMealCost = $memberMeal * $mealRate;
    $memberBalance = $memberDeposit - $memberMealCost;

    $memberDashboard[] = array(
        "userId" => $memberId,
        "Name" => $member["Name"],
        "Avater" => $member["Avater"],
        "Role" => $member["Role"],
        "meal" => $memberMeal,
        "deposit" => $memberDeposit,
        "mealCost" => $memberMealCost,
        "balance" => $memberBalance
    );
}

function dashboardMoney($amount, $currency){
    return number_format(floatval($amount), 2) . " " . htmlspecialchars($currency);
}

function dashboardBalanceClass($amount){
    if($amount < 0) return "text-red";
    if($amount > 0) return "text-green";
    return "";
}
?>

<link rel="stylesheet" href="/app/view/assets/css/dashboard.css">

<div class="dashboard-wrapper">

    
    <div class="dashboard-grid">
        
        <div class="summary-sidebar-col">
            <div class="mess-header-card">
                <div class="mess-meta">
                    <h2><?php echo htmlspecialchars($mess["messName"]); ?>, <?php echo htmlspecialchars($displayMonth); ?></h2>
                    <span class="sub-text">Current Month's Summary</span>
                </div>
                <span class="running-pill">• Running</span>
            </div>

            <div class="metric-list-card">
                <div class="metric-row">
                    <div class="m-left"><span class="m-icon">💰</span><span class="m-label">Mess Balance</span></div>
                    <span class="m-val <?php echo dashboardBalanceClass($totalDeposit - $totalCost); ?>"><?php echo dashboardMoney($totalDeposit - $totalCost, $currency); ?></span>
                </div>
                <div class="metric-row">
                    <div class="m-left"><span class="m-icon">📥</span><span class="m-label">Mess Total Deposit</span></div>
                    <span class="m-val text-green">+<?php echo dashboardMoney($totalDeposit, $currency); ?></span>
                </div>
                <div class="metric-row">
                    <div class="m-left"><span class="m-icon">🍽️</span><span class="m-label">Total Meal</span></div>
                    <span class="m-val"><?php echo number_format($totalMeal, 2); ?></span>
                </div>
                <div class="metric-row">
                    <div class="m-left"><span class="m-icon">🛒</span><span class="m-label">Total Meal Cost</span></div>
                    <span class="m-val"><?php echo dashboardMoney($totalMealCost, $currency); ?></span>
                </div>
                <div class="metric-row">
                    <div class="m-left"><span class="m-icon">📊</span><span class="m-label">Meal Rate</span></div>
                    <span class="m-val"><?php echo dashboardMoney($mealRate, $currency); ?></span>
                </div>
                <a href="/app/controller/components/activeMonthDetails.php" class="btn-detailed-report">বিস্তারিত হিসাব</a>
            </div>
        </div>


        
        <div class="summary-main-col">
            <h3 class="section-title">My Summary</h3>
            <div class="personal-summary-row">
                <div class="summary-widget-card bg-cyan">
                    <div class="w-icon">🍽️</div>
                    <div class="w-amount"><?php echo number_format($myMeal, 2); ?></div>
                    <div class="w-name">My Meal</div>
                </div>
                <div class="summary-widget-card bg-green">
                    <div class="w-icon">💵</div>
                    <div class="w-amount"><?php echo dashboardMoney($myDeposit, $currency); ?></div>
                    <div class="w-name">My Deposit</div>
                </div>
                <div class="summary-widget-card bg-pink">
                    <div class="w-icon">🛒</div>
                    <div class="w-amount"><?php echo dashboardMoney($myMealCost, $currency); ?></div>
                    <div class="w-name">My Meal Cost</div>
                </div>
                <div class="summary-widget-card bg-yellow">
                    <div class="w-icon">⚖️</div>
                    <div class="w-amount <?php echo dashboardBalanceClass($myBalance); ?>"><?php echo dashboardMoney($myBalance, $currency); ?></div>
                    <div class="w-name">My Balance</div>
                </div>
            </div>

            
            <div class="bazar-dates-section bazar-dates-card">
                <div class="bazar-heading-row">
                    <div>
                        <h4>Assigned Bazar Dates</h4>
                        <span class="bazar-subtitle">Your complete bazar duty schedule</span>
                    </div>
                </div>

                <?php if(empty($myBazarDates)){ ?>
                    <div class="bazar-empty-state">
                        <div class="bazar-empty-icon">🗓️</div>
                        <div>
                            <strong>No bazar date assigned</strong>
                            <p>Your manager has not assigned any bazar date to you yet.</p>
                        </div>
                    </div>
                <?php }else{ ?>
                    <div class="bazar-count-row">
                        <div class="bazar-count upcoming-count">
                            <strong><?php echo count($upcomingBazarDates); ?></strong>
                            <span>Upcoming</span>
                        </div>
                        <div class="bazar-count completed-count">
                            <strong><?php echo count($completedBazarDates); ?></strong>
                            <span>Completed</span>
                        </div>
                    </div>

                    <div class="bazar-date-list">
                        <?php foreach($myBazarDates as $bazarDate){
                            $isUpcoming = ($bazarDate >= $today);
                            $label = date("l, d F Y", strtotime($bazarDate));
                        ?>
                            <div class="bazar-date-item <?php echo $isUpcoming ? 'is-upcoming' : 'is-completed'; ?>">
                                <div class="bazar-date-icon">🛒</div>
                                <div class="bazar-date-content">
                                    <strong><?php echo htmlspecialchars($label); ?></strong>
                                    <span><?php echo $isUpcoming ? 'Upcoming bazar duty' : 'Completed bazar duty'; ?></span>
                                </div>
                                <span class="bazar-status"><?php echo $isUpcoming ? 'Upcoming' : 'Completed'; ?></span>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>


    
    <div class="banners-carousel-wrapper">
        <div class="banner-card bg-purple">
            <div class="b-text"><h3>একমাত্র<br>নির্ভরযোগ্য<br>মিল হিসাবের<br>এপ</h3></div>
            <div class="b-badge">100K+ Installs</div>
        </div>
        <a href="https://youtu.be/4e84b-09E5A" target="_blank" class="banner-card bg-red">
            <div class="b-text"><h3>দেখুন কিভাবে এপটি<br>ব্যবহার করবেন ...</h3><span class="b-link">Watch Video &gt;</span></div>
            <div class="b-play-circle">▶</div>
        </a>
        <div class="banner-card bg-teal">
            <div class="b-text"><h3>২০১৯ থেকে হাজারো<br>মেসের আস্থা</h3><span class="b-link">100k on Google Play &gt;</span></div>
        </div>
    </div>


    
    <div class="all-members-section">
        <h3 class="members-heading">All Members</h3>
        <span class="members-subheading">Total <?php echo count($memberDashboard); ?> Members</span>

        <div class="members-summary-grid">
            <?php if(empty($memberDashboard)){ ?>
                <div class="dashboard-no-members">No members found.</div>
            <?php }else{ ?>
                <?php foreach($memberDashboard as $member){
                    $avatar = !empty($member["Avater"]) ? $member["Avater"] : "/app/view/assets/images/defaultAvater.png";
                ?>
                    <div class="member-detail-box">
                        <div class="box-top">
                            <div class="u-info">
                                <div class="u-avatar">
                                    <img src="<?php echo htmlspecialchars($avatar); ?>" alt="Avatar">
                                </div>
                                <div>
                                    <h5><?php echo htmlspecialchars($member["Name"]); ?></h5>
                                    <span class="tag-role">👑 <?php echo htmlspecialchars($member["Role"] ?: "Member"); ?></span>
                                </div>
                            </div>
                            <div class="u-balance">
                                <span class="lbl">Balance</span>
                                <span class="val <?php echo dashboardBalanceClass($member["balance"]); ?>"><?php echo dashboardMoney($member["balance"], $currency); ?></span>
                            </div>
                        </div>
                        <div class="box-grid">
                            <div class="grid-row"><span>Total Meal</span><strong><?php echo number_format($member["meal"], 2); ?></strong></div>
                            <div class="grid-row"><span>Total Deposit</span><strong><?php echo dashboardMoney($member["deposit"], $currency); ?></strong></div>
                            <div class="grid-row highlight"><span>Total Meal Cost</span><strong><?php echo dashboardMoney($member["mealCost"], $currency); ?></strong></div>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>
    </div>
</div>


<script src="/app/view/assets/js/dashboard.js"></script>
