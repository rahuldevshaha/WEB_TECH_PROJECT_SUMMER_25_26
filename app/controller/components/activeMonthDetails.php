<?php

include_once "../../utils/securityValidation.php";
ProtectedRequest("../login/socialLogin.php");

$userId = getSessionValue("userId");
$messId = getSessionValue("messId");

if(empty($messId)){
    $sqlQ = "SELECT messId FROM Member WHERE userId='$userId'";
    $result = exeQuery($sqlQ);
    if(getRowCount($result) > 0){
        $row = getDataRow($result);
        $messId = $row["messId"];
        setSessionValue("messId", $messId);
    }
}

if(empty($messId)){
    header("Location: ../home.php");
    exit();
}

/* =====================================================
   CURRENT USER ROLE
===================================================== */
$myRole = "";
$sqlQ = "SELECT Role FROM Member WHERE messId='$messId' AND userId='$userId'";
$result = exeQuery($sqlQ);
if($result && getRowCount($result) > 0){
    $row = getDataRow($result);
    $myRole = $row["Role"];
}
$isManager = ($myRole == "Manager");

/* =====================================================
   EDIT / DELETE ACTIONS - MANAGER ONLY
===================================================== */
if(isset($_POST["edit_meal_submit"]) ||
   isset($_POST["edit_deposit_submit"]) ||
   isset($_POST["edit_cost_submit"]) ||
   isset($_POST["delete_deposit_submit"]) ||
   isset($_POST["delete_cost_submit"])){

    if(!$isManager){
        die("Only The Manager Can Edit Or Delete Records!");
    }

    /* ---------- EDIT MEAL ---------- */
    if(isset($_POST["edit_meal_submit"])){
        $mealRecordId = addslashes(trim(getValueFromReq("POST", "mealRecordId")));
        $mealDate = addslashes(trim(getValueFromReq("POST", "mealDate")));

        // Toggle: checked = 1, unchecked = 0
        $morning = isset($_POST["morning"]) ? 1 : 0;
        $lunch   = isset($_POST["lunch"]) ? 1 : 0;
        $dinner  = isset($_POST["dinner"]) ? 1 : 0;

        if(empty($mealRecordId) || empty($mealDate)){
            die("Invalid Meal Data!");
        }

        $sqlQ = "UPDATE MealRecord SET
            Morning='$morning',
            Lunch='$lunch',
            Dinner='$dinner',
            mealDate='$mealDate',
            updatedAt=NOW()
            WHERE mealRecordId='$mealRecordId' AND messId='$messId'";

        exeQuery($sqlQ);
        header("Location: " . $_SERVER["REQUEST_URI"]);
        exit();
    }

    /* ---------- EDIT DEPOSIT ---------- */
    if(isset($_POST["edit_deposit_submit"])){
        $fundId = addslashes(trim(getValueFromReq("POST", "fundId")));
        $submittedBy = addslashes(trim(getValueFromReq("POST", "submittedBy")));
        $amountRaw = trim(getValueFromReq("POST", "amount"));
        $note = addslashes(trim(getValueFromReq("POST", "note")));
        $submitDate = addslashes(trim(getValueFromReq("POST", "submitDate")));

        if(empty($fundId) || $amountRaw === "" || !is_numeric($amountRaw) || empty($submitDate)){
            die("Invalid Deposit Data!");
        }

        $amount = floatval($amountRaw);

        $sqlQ = "SELECT userId FROM Member WHERE messId='$messId' AND userId='$submittedBy'";
        $result = exeQuery($sqlQ);
        if(!$result || getRowCount($result) == 0){
            die("Invalid Member!");
        }

        $sqlQ = "UPDATE Funds SET
            amount='$amount',
            note='$note',
            submitDate='$submitDate',
            submittedBy='$submittedBy'
            WHERE fundId='$fundId' AND messId='$messId'";

        exeQuery($sqlQ);
        header("Location: " . $_SERVER["REQUEST_URI"]);
        exit();
    }

    /* ---------- EDIT COST ---------- */
    if(isset($_POST["edit_cost_submit"])){
        $expenseId = addslashes(trim(getValueFromReq("POST", "expenseId")));
        $costType = addslashes(trim(getValueFromReq("POST", "costType")));
        $costBy = addslashes(trim(getValueFromReq("POST", "costBy")));
        $amountRaw = trim(getValueFromReq("POST", "amount"));
        $note = addslashes(trim(getValueFromReq("POST", "note")));
        $costDate = addslashes(trim(getValueFromReq("POST", "costDate")));

        if(empty($expenseId) || empty($costType) || empty($costBy) ||
           $amountRaw === "" || !is_numeric($amountRaw) || empty($costDate)){
            die("Invalid Cost Data!");
        }

        $allowedCostTypes = array("Meal Cost", "Gas Bill", "Electricity Bill", "WiFi Bill", "Other");
        if(!in_array($costType, $allowedCostTypes, true)){
            die("Invalid Cost Type!");
        }

        $amount = floatval($amountRaw);

        $sqlQ = "SELECT userId FROM Member WHERE messId='$messId' AND userId='$costBy'";
        $result = exeQuery($sqlQ);
        if(!$result || getRowCount($result) == 0){
            die("Invalid Member!");
        }

        $sqlQ = "UPDATE Expenses SET
            amount='$amount',
            note='$note',
            costType='$costType',
            costDate='$costDate',
            costBy='$costBy'
            WHERE expenseId='$expenseId' AND messId='$messId'";

        exeQuery($sqlQ);
        header("Location: " . $_SERVER["REQUEST_URI"]);
        exit();
    }

    /* ---------- DELETE DEPOSIT ---------- */
    if(isset($_POST["delete_deposit_submit"])){
        $fundId = addslashes(trim(getValueFromReq("POST", "fundId")));

        if(empty($fundId)){
            die("Invalid Deposit ID!");
        }

        $sqlQ = "DELETE FROM Funds WHERE fundId='$fundId' AND messId='$messId'";
        exeQuery($sqlQ);

        header("Location: " . $_SERVER["REQUEST_URI"]);
        exit();
    }

    /* ---------- DELETE COST ---------- */
    if(isset($_POST["delete_cost_submit"])){
        $expenseId = addslashes(trim(getValueFromReq("POST", "expenseId")));

        if(empty($expenseId)){
            die("Invalid Cost ID!");
        }

        $sqlQ = "DELETE FROM Expenses WHERE expenseId='$expenseId' AND messId='$messId'";
        exeQuery($sqlQ);

        header("Location: " . $_SERVER["REQUEST_URI"]);
        exit();
    }
}

$currentMonth = date("Y-m");
$monthStart = date("Y-m-01");
$monthEnd = date("Y-m-t");
$activeMonth = date("F Y");
$today = date("Y-m-d");

/* =====================================================
   MEMBERS
===================================================== */
$members = array();

$sqlQ = "SELECT u.userId, u.Name, m.Role FROM Member m
INNER JOIN Users u ON m.userId = u.userId
WHERE m.messId='$messId'
ORDER BY m.Role ASC, u.Name ASC";
$result = exeQuery($sqlQ);

if($result){
    while($row = getDataRow($result)){
        $members[] = $row;
    }
}

/* =====================================================
   MEALS
===================================================== */
$mealRecords = array();

$sqlQ = "SELECT mr.mealRecordId, mr.userId, u.Name, mr.Morning, mr.Lunch, mr.Dinner,
mr.mealDate, mr.mealAddedBy
FROM MealRecord mr
INNER JOIN Users u ON mr.userId=u.userId
WHERE mr.messId='$messId'
AND mr.mealDate BETWEEN '$monthStart' AND '$monthEnd'
ORDER BY mr.mealDate ASC, u.Name ASC";
$result = exeQuery($sqlQ);

if($result){
    while($row = getDataRow($result)){
        $mealRecords[] = $row;
    }
}

/* =====================================================
   DEPOSITS
===================================================== */
$depositRecords = array();

$sqlQ = "SELECT f.fundId, f.amount, f.note, f.submitDate, f.submittedBy, f.receivedById,
COALESCE(u.Name, f.submittedBy) AS memberName,
COALESCE(r.Name, f.receivedById) AS receivedByName
FROM Funds f
LEFT JOIN Users u ON f.submittedBy=u.userId
LEFT JOIN Users r ON f.receivedById=r.userId
WHERE f.messId='$messId'
AND f.submitDate BETWEEN '$monthStart' AND '$monthEnd'
ORDER BY f.submitDate ASC, f.createdAt ASC";
$result = exeQuery($sqlQ);

if($result){
    while($row = getDataRow($result)){
        $depositRecords[] = $row;
    }
}

/* =====================================================
   COSTS
===================================================== */
$mealCostRecords = array();
$otherCostRecords = array();

$sqlQ = "SELECT e.expenseId, e.amount, e.note, e.costType, e.costDate, e.costBy, e.assignById,
COALESCE(u.Name, e.costBy) AS memberName,
COALESCE(a.Name, e.assignById) AS assignByName
FROM Expenses e
LEFT JOIN Users u ON e.costBy=u.userId
LEFT JOIN Users a ON e.assignById=a.userId
WHERE e.messId='$messId'
AND e.costDate BETWEEN '$monthStart' AND '$monthEnd'
ORDER BY e.costDate ASC, e.createdAt ASC";
$result = exeQuery($sqlQ);

if($result){
    while($row = getDataRow($result)){
        if($row["costType"] == "Meal Cost"){
            $mealCostRecords[] = $row;
        }else{
            $otherCostRecords[] = $row;
        }
    }
}

/* =====================================================
   BAZAR - MEMBER WISE CURRENT MONTH
   AssignBazar.bazarDates is JSON array of dates.
===================================================== */
$bazarMemberRecords = array();

foreach($members as $member){
    $bazarDates = array();

    $memberUserId = $member["userId"];
    $sqlQ = "SELECT bazarDates FROM AssignBazar
             WHERE messId='$messId' AND userId='$memberUserId'";
    $result = exeQuery($sqlQ);

    if($result){
        while($row = getDataRow($result)){
            $decoded = json_decode($row["bazarDates"], true);
            if(is_array($decoded)){
                foreach($decoded as $dateValue){
                    $dateValue = trim((string)$dateValue);
                    if(preg_match('/^\\d{4}-\\d{2}-\\d{2}$/', $dateValue) &&
                       $dateValue >= $monthStart && $dateValue <= $monthEnd){
                        $bazarDates[] = $dateValue;
                    }
                }
            }
        }
    }

    $bazarDates = array_values(array_unique($bazarDates));
    sort($bazarDates);

    $bazarMemberRecords[] = array(
        "userId" => $memberUserId,
        "Name" => $member["Name"],
        "bazarDates" => $bazarDates,
        "bazarCount" => count($bazarDates)
    );
}

/* =====================================================
   HISAB
===================================================== */
$hisabTotalMeal = 0;
$hisabTotalDeposit = 0;
$hisabTotalCost = 0;
$hisabMemberMeals = array();
$hisabMemberDeposits = array();

foreach($members as $member){
    $hisabMemberMeals[$member["userId"]] = 0;
    $hisabMemberDeposits[$member["userId"]] = 0;
}

foreach($mealRecords as $record){
    if($record["mealDate"] <= $today){
        $memberMeal = floatval($record["Morning"]) + floatval($record["Lunch"]) + floatval($record["Dinner"]);
        $hisabTotalMeal += $memberMeal;

        if(isset($hisabMemberMeals[$record["userId"]])){
            $hisabMemberMeals[$record["userId"]] += $memberMeal;
        }
    }
}

foreach($depositRecords as $deposit){
    if($deposit["submitDate"] <= $today){
        $amount = floatval($deposit["amount"]);
        $hisabTotalDeposit += $amount;

        if(isset($hisabMemberDeposits[$deposit["submittedBy"]])){
            $hisabMemberDeposits[$deposit["submittedBy"]] += $amount;
        }
    }
}

foreach($mealCostRecords as $cost){
    if($cost["costDate"] <= $today){
        $hisabTotalCost += floatval($cost["amount"]);
    }
}

$hisabMealRate = $hisabTotalMeal > 0 ? $hisabTotalCost / $hisabTotalMeal : 0;

define('ACTIVE_MONTH_DETAILS_CONTROLLER_LOADED', true);
require_once __DIR__ . "/../../view/components/activeMonthDetails.php";

?>
