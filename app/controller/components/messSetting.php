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


$isErr = false;
$errorMessage = "";
$msg = "";


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
