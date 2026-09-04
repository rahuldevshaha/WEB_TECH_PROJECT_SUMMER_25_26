<?php

require_once __DIR__ . "/../../utils/securityValidation.php";
ProtectedRequest("../login/socialLogin.php");

$userId = getSessionValue("userId");
$messId = getSessionValue("messId");

// If messId is not already stored in session, find the user's first mess.
if (empty($messId)) {
    $sql = "SELECT messId FROM Member WHERE userId='$userId' LIMIT 1";
    $result = exeQuery($sql);

    if ($result && getRowCount($result) > 0) {
        $row = getDataRow($result);
        $messId = $row["messId"];
        setSessionValue("messId", $messId);
    }
}

if (empty($messId)) {
    echo '<script>window.location.href = "../../view/home.php";</script>';
    exit();
}

$historyRecords = array();
$messName = "Mess";
$currency = "BDT";

// Mess information
$sql = "SELECT messName, Currency FROM Messes WHERE messId='$messId' LIMIT 1";
$result = exeQuery($sql);
if ($result && getRowCount($result) > 0) {
    $row = getDataRow($result);
    $messName = $row["messName"] ?? "Mess";
    $currency = $row["Currency"] ?? "BDT";
}

// History records for the current mess
$sql = "SELECT historyId, month, totalMember, totalMeal, totalExpense,
               mealRate, totalFund, totalDue, createdAt, updatedAt
        FROM History
        WHERE messId='$messId'
        ORDER BY createdAt DESC, historyId DESC";
$result = exeQuery($sql);

if ($result) {
    while ($row = getDataRow($result)) {
        $historyRecords[] = $row;
    }
}

require_once __DIR__ . "/../../view/components/history.php";
?>
