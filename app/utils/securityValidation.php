<?php



require __DIR__ . "/utils.php";

require __DIR__ . "/../model/dbAccess.php";


startSession();
function ProtectedRequest($redirectPath){

    $check = checkSessionValidity("userId");
    if(!$check){
        endSession();
        header("Location: $redirectPath");
        exit();
    }
}



function UnprotectedRequest($redirectPath){
    $check= checkSessionValidity("userId");
    if($check){
        header("Location: $redirectPath");
    }
}




function getVerifiedMessId($userId){

    $cachedMessId = getSessionValue("messId");

    if(!empty($cachedMessId)){

        $sqlQ = "SELECT messId FROM Member WHERE userId='$userId' AND messId='$cachedMessId'";
        $result = exeQuery($sqlQ);

        if($result && getRowCount($result) > 0){
            return $cachedMessId;
        }

        
        setSessionValue("messId", "");
    }

    
    $sqlQ = "SELECT messId FROM Member WHERE userId='$userId' LIMIT 1";
    $result = exeQuery($sqlQ);

    if($result && getRowCount($result) > 0){
        $row = getDataRow($result);
        $freshMessId = $row["messId"];
        setSessionValue("messId", $freshMessId);
        return $freshMessId;
    }

    return "";
}


?>