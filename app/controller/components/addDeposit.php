<?php

include_once "../../utils/securityValidation.php";
ProtectedRequest("../login/socialLogin.php");


$userId = getSessionValue("userId");

$messId = getVerifiedMessId($userId);

if(empty($messId)){
    header("Location: ../home.php");
    exit();
}



$members = array();

$sqlQ = "SELECT u.userId, u.Name FROM Member m, Users u WHERE m.userId=u.userId AND m.messId='$messId'";
$result = exeQuery($sqlQ);

while($row = getDataRow($result)){
    $members[] = $row;
}


$depositDate = date("Y-m-d");
$amount = "";
$note = "";
$member = "";

$isErr = false;
$errorMessage = "";
$msg = "";
$showSuccessModal = false;


function ResetAllField(){
    global $depositDate, $amount, $note, $member, $isErr;

    $depositDate = date("Y-m-d");
    $amount = "";
    $note = "";
    $member = "";

    $isErr = false;
}


if(reqMethodCheck("POST")){

    $depositDate = getValueFromReq("POST", "deposit_date");
    $amount = getValueFromReq("POST", "amount");
    $note = getValueFromReq("POST", "note");
    $member = getValueFromReq("POST", "member");


    function isInvalidInputs(){
        global $amount, $member, $members, $isErr;

        if(empty($amount) || !checkIsNumber($amount) || $amount == 0){
            $isErr = true;
            return "Enter Valid Amount!";
        }

        if(empty($member)){
            $isErr = true;
            return "Select Who Has Deposited!";
        }

        $found = false;
        foreach($members as $m){
            if($m["userId"] == $member){
                $found = true;
            }
        }
        if(!$found){
            $isErr = true;
            return "Select Who Has Deposited!";
        }

        return "";
    }

    $errorMessage = isInvalidInputs();


    if(!$isErr){

        $fundId = generatePkID("fund");

        $sqlQ = "INSERT INTO Funds (fundId, messId, amount, note, submitDate, submittedBy, receivedById) VALUES
        ('$fundId', '$messId', '$amount', '$note', '$depositDate', '$member', '$userId')";

        $result = exeQuery($sqlQ);

        if($result){
            $isSuccess = true;
            $msg = "Deposit Added Successfully!";
            $showSuccessModal = true;
            ResetAllField();
        }else{
            $isErr = true;
            $errorMessage = "Failed To Add Deposit!";
        }
    }
}



require_once __DIR__ . "/../../view/components/addDeposit.php";

?>
