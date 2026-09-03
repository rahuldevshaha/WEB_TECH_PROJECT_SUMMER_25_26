<?php




include_once "../../utils/securityValidation.php";
ProtectedRequest("../login/socialLogin.php");

$userId = getSessionValue("userId");
$messId = getSessionValue("messId");

if (empty($messId)) {
    $sqlQ = "SELECT messId FROM Member WHERE userId='$userId' LIMIT 1";
    $result = exeQuery($sqlQ);

    if ($result && getRowCount($result) > 0) {
        $row = getDataRow($result);
        $messId = $row["messId"];
        setSessionValue("messId", $messId);
    }
}

if (empty($messId)) {
    echo '<script>window.location.href = "../home.php";</script>';
    exit();
}

$today = date("Y-m-d");
$currentMonth = date("Y-m");
$currentMonthName = date("F");
$currentYear = date("Y");

$messName = "Mess";
$currency = "BDT";


$sqlQ = "SELECT messName, Currency FROM Messes WHERE messId='$messId' LIMIT 1";
$result = exeQuery($sqlQ);
if ($result && getRowCount($result) > 0) {
    $row = getDataRow($result);
    $messName = $row["messName"] ?? "Mess";
    $currency = $row["Currency"] ?? "BDT";
}


$members = array();
$sqlQ = "SELECT u.userId, u.Name, u.Avater, m.Role
         FROM Member m
         INNER JOIN Users u ON m.userId=u.userId
         WHERE m.messId='$messId'
         ORDER BY m.Role ASC, u.Name ASC";
$result = exeQuery($sqlQ);

if ($result) {
    while ($row = getDataRow($result)) {
        $members[] = $row;
    }
}


$mealByUser = array();
$totalMeal = 0;

$sqlQ = "SELECT userId,
                COALESCE(SUM(Morning),0) AS morning,
                COALESCE(SUM(Lunch),0) AS lunch,
                COALESCE(SUM(Dinner),0) AS dinner
         FROM MealRecord
         WHERE messId='$messId'
           AND mealDate >= '$currentMonth-01'
           AND mealDate <= '$today'
         GROUP BY userId";
$result = exeQuery($sqlQ);

if ($result) {
    while ($row = getDataRow($result)) {
        $meal = (float)$row["morning"] + (float)$row["lunch"] + (float)$row["dinner"];
        $mealByUser[$row["userId"]] = $meal;
        $totalMeal += $meal;
    }
}


$depositByUser = array();
$totalDeposit = 0;

$sqlQ = "SELECT submittedBy, COALESCE(SUM(amount),0) AS totalDeposit
         FROM Funds
         WHERE messId='$messId'
           AND submitDate >= '$currentMonth-01'
           AND submitDate <= '$today'
         GROUP BY submittedBy";
$result = exeQuery($sqlQ);

if ($result) {
    while ($row = getDataRow($result)) {
        $deposit = (float)$row["totalDeposit"];
        $depositByUser[$row["submittedBy"]] = $deposit;
        $totalDeposit += $deposit;
    }
}


$totalCost = 0;
$totalMealCost = 0;
$totalOtherCost = 0;

$sqlQ = "SELECT costType, COALESCE(SUM(amount),0) AS totalAmount
         FROM Expenses
         WHERE messId='$messId'
           AND costDate >= '$currentMonth-01'
           AND costDate <= '$today'
         GROUP BY costType";
$result = exeQuery($sqlQ);

if ($result) {
    while ($row = getDataRow($result)) {
        $amount = (float)$row["totalAmount"];
        $costType = trim((string)$row["costType"]);

        $totalCost += $amount;

        if (strcasecmp($costType, "Meal Cost") === 0) {
            $totalMealCost += $amount;
        } else {
            $totalOtherCost += $amount;
        }
    }
}

$mealRate = $totalMeal > 0 ? ($totalMealCost / $totalMeal) : 0;
$messBalance = $totalDeposit - $totalCost;


$myMeal = isset($mealByUser[$userId]) ? (float)$mealByUser[$userId] : 0;
$myDeposit = isset($depositByUser[$userId]) ? (float)$depositByUser[$userId] : 0;
$myMealCost = $myMeal * $mealRate;
$myBalance = $myDeposit - $myMealCost;


$bazarDates = array();
$sqlQ = "SELECT bazarDates
         FROM AssignBazar
         WHERE messId='$messId' AND userId='$userId'
         LIMIT 1";
$result = exeQuery($sqlQ);

if ($result && getRowCount($result) > 0) {
    $row = getDataRow($result);
    $decodedDates = json_decode($row["bazarDates"], true);

    if (is_array($decodedDates)) {
        foreach ($decodedDates as $dateValue) {
            if (is_string($dateValue) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateValue)) {
                $dateObject = DateTime::createFromFormat("Y-m-d", $dateValue);
                if ($dateObject && $dateObject->format("Y-m-d") === $dateValue) {
                    $bazarDates[] = $dateValue;
                }
            }
        }
    }
}

$bazarDates = array_values(array_unique($bazarDates));
sort($bazarDates);

$upcomingBazarDates = array();
$completedBazarDates = array();

foreach ($bazarDates as $bazarDate) {
    if ($bazarDate >= $today) {
        $upcomingBazarDates[] = $bazarDate;
    } else {
        $completedBazarDates[] = $bazarDate;
    }
}


$memberDashboard = array();

foreach ($members as $member) {
    $memberId = $member["userId"];
    $memberMeal = isset($mealByUser[$memberId]) ? (float)$mealByUser[$memberId] : 0;
    $memberDeposit = isset($depositByUser[$memberId]) ? (float)$depositByUser[$memberId] : 0;
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

?>














