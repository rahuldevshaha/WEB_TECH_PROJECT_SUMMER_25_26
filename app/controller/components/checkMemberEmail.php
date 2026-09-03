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
    }
}



$sqlQ = "SELECT Role FROM Member WHERE messId='$messId' AND userId='$userId'";
$result = exeQuery($sqlQ);
$row = getDataRow($result);
$myRole = $row ? $row["Role"] : "";

header("Content-Type: application/json");

if($myRole != "Manager"){
    echo json_encode(array("found" => false));
    exit();
}


$email = getValueFromReq("GET", "email");

if(empty($email) || !checkEmail($email)){
    echo json_encode(array("found" => false));
    exit();
}

$sqlQ = "SELECT Name FROM Users WHERE Email='$email'";
$result = exeQuery($sqlQ);

if(getRowCount($result) > 0){
    $row = getDataRow($result);
    echo json_encode(array("found" => true, "name" => $row["Name"]));
}else{
    echo json_encode(array("found" => false));
}

?>
