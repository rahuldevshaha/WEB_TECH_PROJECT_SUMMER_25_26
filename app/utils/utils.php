<?php


date_default_timezone_set('Asia/Dhaka');


function startSession() {
    if(session_status() === PHP_SESSION_NONE){
        session_start();
    }
}


function checkSessionValidity($fieldName){
    if(isset($_SESSION[$fieldName])){
        return true;
    }
    return false;
}


function getSessionValue($fieldName){
    $check=checkSessionValidity($fieldName);
    return $check? $_SESSION[$fieldName] : "";
}


function setSessionValue($fieldName, $value){
    $_SESSION[$fieldName] = $value;
}


function endSession(){
    session_unset();
    session_destroy();
}








function reqMethodCheck($reqType){
   return $_SERVER["REQUEST_METHOD"] == $reqType? true: false;
}


function getValueFromReq($reqType, $fieldName){
    if($reqType == "POST"){
        return $_POST[$fieldName];
    }else if($reqType == "GET"){
        return $_GET[$fieldName];
    }
    return "";
}









function checkValidName($name, $minLength = 3){

    $name = trim($name);
    if(strlen($name) < $minLength) return false;
    if(!preg_match("/^[a-zA-Z ]+$/", $name)) return false;
    return true;
}



function checkEmail($email){
    return filter_var(trim($email), FILTER_VALIDATE_EMAIL) !== false;
}



function checkPhone($phone){
    $phone = str_replace(['-', ' '], '', trim($phone));

    if(preg_match('/^\+8801[3-9][0-9]{8}$/', $phone)) return true;
    if(preg_match('/^8801[3-9][0-9]{8}$/', $phone)) return true;
    if(preg_match('/^01[3-9][0-9]{8}$/', $phone)) return true;

    return false;
}



function checkPassword($password, $minlengthHaveToBe=6, $isMix=true){
    if(strlen($password) < $minlengthHaveToBe) return false;
    if($isMix){
        if(!preg_match("/[A-Z]/", $password)) return false;
        if(!preg_match("/[a-z]/", $password)) return false;
        if(!preg_match("/[0-9]/", $password)) return false;
    }
    return true;
}



function checkIsNumber($number){
    return is_numeric($number);
}



function generatePkID($prefix)
{
    return $prefix . random_int(10000, 99999);
}


function hashPassword($password){
    return password_hash($password, PASSWORD_DEFAULT);
}








function getMealCookHour24($cookHour, $isEveningMeal = false){
    $hour = intval($cookHour);

    if($isEveningMeal && $hour < 12){
        $hour += 12;
    }

    return $hour;
}



function getMealDeadline($mealDate, $cookHour, $isEveningMeal = false){
    $cookHour24 = getMealCookHour24($cookHour, $isEveningMeal);

    $deadline = new DateTime($mealDate);
    $deadline->setTime($cookHour24, 0, 0);
    $deadline->modify("-3 hours");

    return $deadline;
}






function canSetMeal($mealDate, $mealType, $messRow, $isManager){

    if($isManager){
        return array(true, "");
    }

    if($mealType == "Morning"){
        $cookHour   = $messRow["mealCookTimeForMorning"];
        $isEvening  = false;
    }else if($mealType == "Lunch"){
        $cookHour   = $messRow["mealCookLastTimeForLunch"];
        $isEvening  = false;
    }else if($mealType == "Dinner"){
        $cookHour   = $messRow["mealCookLastTimeForNight"];
        $isEvening  = true;
    }else{
        return array(false, "Invalid Meal Type!");
    }

    $deadline = getMealDeadline($mealDate, $cookHour, $isEvening);
    $now = new DateTime();

    if($now >= $deadline){
        return array(false, "$mealType Meal Deadline Passed (" . $deadline->format("d M, h:i A") . "). Only The Manager Can Edit Now.");
    }

    return array(true, "");
}





	
	
	
	
	
	
	
	
	
	
	
	
    


?>
