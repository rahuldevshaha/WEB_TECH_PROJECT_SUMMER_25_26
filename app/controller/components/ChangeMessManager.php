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
$myRole = $row ? $row["Role"] : "";

if($myRole != "Manager"){
    header("Location: ../home.php");
    exit();
}


$isErr = false;
$errorMessage = "";
$msg = "";


if(reqMethodCheck("POST")){

    $newManagerId = getValueFromReq("POST", "new_manager_id");

    if(empty($newManagerId)){
        $isErr = true;
        $errorMessage = "Please Select A Member To Make Manager!";

    }else if($newManagerId == $userId){
        $isErr = true;
        $errorMessage = "You Are Already The Manager!";

    }else{

        
        $sqlQ = "SELECT Role FROM Member WHERE messId='$messId' AND userId='$newManagerId'";
        $result = exeQuery($sqlQ);
        $targetRow = getDataRow($result);

        if(!$targetRow){
            $isErr = true;
            $errorMessage = "Invalid Member Selected!";

        }else{

            
            $sqlQ = "UPDATE Member SET Role='Member' WHERE messId='$messId' AND userId='$userId'";
            $result = exeQuery($sqlQ);

            if($result){

                $sqlQ = "UPDATE Member SET Role='Manager' WHERE messId='$messId' AND userId='$newManagerId'";
                $result = exeQuery($sqlQ);

                if($result){
                    $msg = "Mess Manager Changed Successfully!";
                    
                    $myRole = "Member";
                }else{
                    
                    $sqlQ = "UPDATE Member SET Role='Manager' WHERE messId='$messId' AND userId='$userId'";
                    exeQuery($sqlQ);

                    $isErr = true;
                    $errorMessage = "Failed To Assign New Manager!";
                }

            }else{
                $isErr = true;
                $errorMessage = "Failed To Update Manager!";
            }
        }
    }
}



$otherMembers = array();

$sqlQ = "SELECT u.userId, u.Name, u.Email FROM Member m, Users u
WHERE m.userId=u.userId AND m.messId='$messId' AND m.userId!='$userId'
ORDER BY u.Name ASC";
$result = exeQuery($sqlQ);

while($row = getDataRow($result)){
    $otherMembers[] = $row;
}



require_once __DIR__ . "/../../view/components/ChangeMessManager.php";

?>
