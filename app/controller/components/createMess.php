<?php

include_once "../../utils/securityValidation.php";
ProtectedRequest("../login/socialLogin.php");


$userId = getSessionValue("userId");

$messName = "";
$monthName = "";

$isErr = false;
$errorMessage = "";
$showModal = false;


function ResetAllField(){
    global $messName, $monthName, $isErr;

    $messName = "";
    $monthName = "";

    $isErr = false;
}



$sqlQ = "SELECT messId FROM Member WHERE userId='$userId'";
$result = exeQuery($sqlQ);

if(getRowCount($result) > 0){
    header("Location: ../home.php");
    exit();
}


if(reqMethodCheck("POST")){

    $messName = getValueFromReq("POST", "messName");
    $monthName = getValueFromReq("POST", "monthName");


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

        $messId = generatePkID("mess");

        if(empty($monthName)){
            $monthName = date("Y-m-d");
        }

        $sqlQ = "INSERT INTO Messes (messId, createdBy, messName, messCreateDate) VALUES
        ('$messId', '$userId', '$messName', '$monthName')";

        $result = exeQuery($sqlQ);

        if($result){

            $sqlQ = "INSERT INTO Member (messId, userId, Role, AddedBy) VALUES
            ('$messId', '$userId', 'Manager', '$userId')";

            $result = exeQuery($sqlQ);

            if($result){
                $isSuccess = true;
                setSessionValue("messId", $messId);

                $showModal = true;
                ResetAllField();
            }else{
                $isErr = true;
                $errorMessage = "Failed To Add You As Member!";
            }

        }else{
            $isErr = true;
            $errorMessage = "Failed To Create Mess!";
        }
    }
}



require_once __DIR__ . "/../../view/components/createMess.php";

?>
