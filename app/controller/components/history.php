<?php

require_once __DIR__ . "/../../utils/securityValidation.php";
ProtectedRequest("../login/socialLogin.php");

$userId = getSessionValue("userId");
$messId = getVerifiedMessId($userId);

if (empty($messId)) {
    echo '<script>window.location.href = "../../view/home.php";</script>';
    exit();
}

$historyRecords = array();
$messName = "Mess";
$currency = "BDT";


$sql = "SELECT messName, Currency FROM Messes WHERE messId='$messId' LIMIT 1";
$result = exeQuery($sql);
if ($result && getRowCount($result) > 0) {
    $row = getDataRow($result);
    $messName = $row["messName"] ?? "Mess";
    $currency = $row["Currency"] ?? "BDT";
}


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
