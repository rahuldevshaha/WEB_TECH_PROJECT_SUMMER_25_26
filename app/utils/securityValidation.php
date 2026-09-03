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


?>