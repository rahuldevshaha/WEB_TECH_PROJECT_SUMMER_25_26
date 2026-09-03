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




$currentMonth = date("Y-m");
$monthStart = date("Y-m-01");
$monthEnd = date("Y-m-t");
$activeMonth = date("F Y");





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





$depositRecords = array();

$sqlQ = "SELECT f.fundId, f.amount, f.note, f.submitDate, f.submittedBy,
COALESCE(u.Name, f.submittedBy) AS memberName
FROM Funds f
LEFT JOIN Users u ON f.submittedBy=u.userId
WHERE f.messId='$messId'
AND f.submitDate BETWEEN '$monthStart' AND '$monthEnd'
ORDER BY f.submitDate ASC, f.createdAt ASC";
$result = exeQuery($sqlQ);

if($result){
    while($row = getDataRow($result)){
        $depositRecords[] = $row;
    }
}





$mealCostRecords = array();
$otherCostRecords = array();

$sqlQ = "SELECT e.expenseId, e.amount, e.note, e.costType, e.costDate, e.costBy,
COALESCE(u.Name, e.costBy) AS memberName
FROM Expenses e
LEFT JOIN Users u ON e.costBy=u.userId
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
    if($record["mealDate"] <= date("Y-m-d")){
        $memberMeal = floatval($record["Morning"]) + floatval($record["Lunch"]) + floatval($record["Dinner"]);
        $hisabTotalMeal += $memberMeal;

        if(isset($hisabMemberMeals[$record["userId"]])){
            $hisabMemberMeals[$record["userId"]] += $memberMeal;
        }
    }
}

foreach($depositRecords as $deposit){
    if($deposit["submitDate"] <= date("Y-m-d")){
        $amount = floatval($deposit["amount"]);
        $hisabTotalDeposit += $amount;

        if(isset($hisabMemberDeposits[$deposit["submittedBy"]])){
            $hisabMemberDeposits[$deposit["submittedBy"]] += $amount;
        }
    }
}

foreach($mealCostRecords as $cost){
    if($cost["costDate"] <= date("Y-m-d")){
        $hisabTotalCost += floatval($cost["amount"]);
    }
}

$hisabMealRate = $hisabTotalMeal > 0 ? $hisabTotalCost / $hisabTotalMeal : 0;





define('ACTIVE_MONTH_DETAILS_CONTROLLER_LOADED', true);
require_once __DIR__ . "/../../view/components/activeMonthDetails.php";

?>
