<?php

include_once "../../utils/securityValidation.php";
ProtectedRequest("../login/socialLogin.php");


$userId = getSessionValue("userId");
$currentUserId = $userId;



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



$members = array();

$sqlQ = "SELECT u.userId, u.Name, m.Role FROM Member m INNER JOIN Users u ON m.userId = u.userId WHERE m.messId='$messId' ORDER BY m.Role ASC, u.Name ASC";
$result = exeQuery($sqlQ);

if($result){
    while($row = getDataRow($result)){
        $members[] = $row;
    }
}


if(empty($members)){

    $sqlQ = "SELECT userId, Name FROM Users WHERE userId='$userId'";
    $result = exeQuery($sqlQ);

    if($result && getRowCount($result) > 0){
        $row = getDataRow($result);
        $members[] = array("userId" => $row["userId"], "Name" => $row["Name"], "Role" => "Manager");
    }
}



$costTypeOptions = array("Meal Cost", "Gas Bill", "Electricity Bill", "WiFi Bill", "Other");


$isErr = false;
$errorMessage = "";
$showModal = false;


if(reqMethodCheck("POST") && isset($_POST["add_cost_submit"])){

    $costType = getValueFromReq("POST", "costType");
    $costDate = getValueFromReq("POST", "costDate");
    $amount = getValueFromReq("POST", "amount");
    $note = getValueFromReq("POST", "note");
    $costBy = getValueFromReq("POST", "costBy");
    $autoFund = isset($_POST["auto_fund"]);


    function isInvalidInputs(){
        global $costType, $costTypeOptions, $costDate, $amount, $costBy, $members, $isErr;

        if(empty($costType) || !in_array($costType, $costTypeOptions)){
            $isErr = true;
            return "Select A Valid Cost Type!";
        }

        if(empty($costDate)){
            $isErr = true;
            return "Select A Valid Date!";
        }

        if(empty($amount) || !checkIsNumber($amount) || $amount <= 0){
            $isErr = true;
            return "Enter Valid Amount!";
        }

        $found = false;
        foreach($members as $m){
            if($m["userId"] == $costBy){
                $found = true;
            }
        }

        if(empty($costBy) || !$found){
            $isErr = true;
            return "Select A Valid Shopper!";
        }

        return "";
    }

    $errorMessage = isInvalidInputs();


    if(!$isErr){

        
        $expenseId = generatePkID("exp");

        $sqlQ = "INSERT INTO Expenses (expenseId, messId, amount, note, costType, costDate, costBy, assignById) VALUES
        ('$expenseId', '$messId', '$amount', '$note', '$costType', '$costDate', '$costBy', NULL)";

        $result = exeQuery($sqlQ);

        if($result){

            
            if($autoFund){

                $fundId = generatePkID("fund");

                $sqlQ2 = "INSERT INTO Funds (fundId, messId, amount, note, submitDate, submittedBy, receivedById) VALUES
                ('$fundId', '$messId', '$amount', 'Auto Fund For $costType', '$costDate', '$costBy', '$userId')";

                exeQuery($sqlQ2);
            }

            $showModal = true;

        }else{
            $isErr = true;
            $errorMessage = "Failed To Add Cost!";
        }
    }
}



require_once __DIR__ . "/../../view/components/addCost.php";

?>
