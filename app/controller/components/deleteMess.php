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



$sqlQ = "SELECT Role FROM Member WHERE messId='$messId' AND userId='$userId'";
$result = exeQuery($sqlQ);
$row = getDataRow($result);

if(!$row || $row["Role"] != "Manager"){
    header("Location: ../home.php");
    exit();
}


$confirmText = "";

$isErr = false;
$errorMessage = "";


function ResetAllField(){
    global $confirmText, $isErr;

    $confirmText = "";
    $isErr = false;
}


if(reqMethodCheck("POST")){

    $confirmText = getValueFromReq("POST", "confirmText");


    function isInvalidInputs(){
        global $confirmText, $isErr;

        if($confirmText != "Delete all months calculation"){
            $isErr = true;
            return "Confirmation text does not match!";
        }

        return "";
    }

    $errorMessage = isInvalidInputs();


    if(!$isErr){

        $sqlQ = "DELETE FROM Messes WHERE messId='$messId'";
        $result = exeQuery($sqlQ);

        if($result){
            setSessionValue("messId", "");
            ResetAllField();

            header("Location: ../components/createMess.php");
            exit();
        }else{
            $isErr = true;
            $errorMessage = "Failed To Delete Mess!";
        }
    }
}



require_once __DIR__ . "/../../view/components/deleteMess.php";

?>
