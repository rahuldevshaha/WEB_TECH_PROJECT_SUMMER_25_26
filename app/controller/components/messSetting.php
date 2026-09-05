<?php

include_once "../../utils/securityValidation.php";
ProtectedRequest("../login/socialLogin.php");


$userId = getSessionValue("userId");
$messId = getVerifiedMessId($userId);

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


$isErr = false;
$errorMessage = "";
$msg = "";
$showSuccessModal = false;


if(reqMethodCheck("POST")){

    $messName = getValueFromReq("POST", "mess_name");
    $autoTranferBal = getValueFromReq("POST", "autoTranferBal") == "on" ? 1 : 0;


    function isInvalidInputs(){
        global $messName, $isErr;

        if(empty($messName) || !checkValidName($messName, 3)){
            $isErr = true;
            return "Enter Valid Mess Name!";
        }

        return "";
    }

    $errorMessage = isInvalidInputs();


    if(!$isErr){

        $sqlQ = "UPDATE Messes SET messName='$messName', autoTransferBalance=$autoTranferBal WHERE messId='$messId'";
        $result = exeQuery($sqlQ);

        if($result){
            $msg = "Mess Settings Updated Successfully!";
            $showSuccessModal = true;
        }else{
            $isErr = true;
            $errorMessage = "Failed To Update Settings!";
        }
    }
}



$sqlQ = "SELECT messName, Currency, autoTransferBalance FROM Messes WHERE messId='$messId'";
$result = exeQuery($sqlQ);
$messRow = getDataRow($result);

$messName = $messRow["messName"];
$messCurrency = $messRow["Currency"];
$autoTranferBal = $messRow["autoTransferBalance"];



require_once __DIR__ . "/../../view/components/messSetting.php";

?>
