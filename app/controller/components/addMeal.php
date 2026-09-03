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
$isManager = ($myRole == "Manager");



$sqlQ = "SELECT * FROM Messes WHERE messId='$messId'";
$result = exeQuery($sqlQ);
$messRow = getDataRow($result);



$members = array();

$sqlQ = "SELECT u.userId, u.Name, m.Role FROM Member m INNER JOIN Users u ON m.userId = u.userId
WHERE m.messId='$messId' ORDER BY m.Role ASC, u.Name ASC";
$result = exeQuery($sqlQ);

while($row = getDataRow($result)){
    $members[] = $row;
}


$isErr = false;
$errorMessage = "";
$message = "";
$blockedNotices = array();



$breakfast = getSessionValue("mealDefault_breakfast");
$lunch     = getSessionValue("mealDefault_lunch");
$dinner    = getSessionValue("mealDefault_dinner");

$breakfast = ($breakfast === "") ? 0 : floatval($breakfast);
$lunch     = ($lunch === "")     ? 0 : floatval($lunch);
$dinner    = ($dinner === "")    ? 0 : floatval($dinner);


$mealDate = date("Y-m-d");





if(reqMethodCheck("POST") && isset($_POST["set_meal"])){

    
    $breakfast = isset($_POST["modal_breakfast"]) ? 1 : 0;
    $lunch     = isset($_POST["modal_lunch"]) ? 1 : 0;
    $dinner    = isset($_POST["modal_dinner"]) ? 1 : 0;

    setSessionValue("mealDefault_breakfast", $breakfast);
    setSessionValue("mealDefault_lunch", $lunch);
    setSessionValue("mealDefault_dinner", $dinner);

    $message = "Meal Values Set! Now Select Members & Date To Apply.";
}






if(reqMethodCheck("POST") && isset($_POST["add_meal_submit"])){

    $memberSelect   = getValueFromReq("POST", "memberSelect");
    $mealDate       = getValueFromReq("POST", "mealDate");
    $reqBreakfast   = getValueFromReq("POST", "breakfast_val");
    $reqLunch       = getValueFromReq("POST", "lunch_val");
    $reqDinner      = getValueFromReq("POST", "dinner_val");


    function isInvalidMealInputs(){
        global $mealDate, $reqBreakfast, $reqLunch, $reqDinner, $isErr;

        if(empty($mealDate) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $mealDate)){
            $isErr = true;
            return "Select A Valid Meal Date!";
        }

        if(!checkIsNumber($reqBreakfast) || !checkIsNumber($reqLunch) || !checkIsNumber($reqDinner)
            || $reqBreakfast < 0 || $reqLunch < 0 || $reqDinner < 0){
            $isErr = true;
            return "Enter Valid Meal Numbers!";
        }

        return "";
    }

    $errorMessage = isInvalidMealInputs();


    if(!$isErr){

        $reqBreakfast = floatval($reqBreakfast);
        $reqLunch     = floatval($reqLunch);
        $reqDinner    = floatval($reqDinner);

        
        
        $targetUserIds = array();

        if($isManager && $memberSelect === "all"){
            foreach($members as $m){
                $targetUserIds[] = $m["userId"];
            }
        }else if($isManager && !empty($memberSelect)){

            $found = false;
            foreach($members as $m){
                if($m["userId"] == $memberSelect) $found = true;
            }

            if($found){
                $targetUserIds[] = $memberSelect;
            }else{
                $isErr = true;
                $errorMessage = "Selected Member Is Not Part Of This Mess!";
            }

        }else{
            $targetUserIds[] = $userId;
        }


        if(!$isErr){

            $mealTypeMap = array(
                "Morning" => array("enabled" => $messRow["isMorningMeal"], "value" => $reqBreakfast, "col" => "Morning"),
                "Lunch"   => array("enabled" => $messRow["isLunchMeal"],   "value" => $reqLunch,     "col" => "Lunch"),
                "Dinner"  => array("enabled" => $messRow["isDinnerMeal"],  "value" => $reqDinner,    "col" => "Dinner"),
            );

            $anyRowSaved = false;

            foreach($targetUserIds as $tUserId){

                
                
                $sqlQ = "SELECT mealRecordId, Morning, Lunch, Dinner FROM MealRecord
                WHERE messId='$messId' AND userId='$tUserId' AND mealDate='$mealDate'";
                $result = exeQuery($sqlQ);

                $existing = (getRowCount($result) > 0) ? getDataRow($result) : null;

                $finalValues = array();

                foreach($mealTypeMap as $mealType => $info){

                    $oldValue = $existing ? floatval($existing[$info["col"]]) : 0;

                    if(!$info["enabled"]){
                        
                        $finalValues[$info["col"]] = 0;
                        continue;
                    }

                    $newValue = $info["value"];

                    if($newValue == $oldValue){
                        
                        $finalValues[$info["col"]] = $oldValue;
                        continue;
                    }

                    list($allowed, $reason) = canSetMeal($mealDate, $mealType, $messRow, $isManager);

                    if($allowed){
                        $finalValues[$info["col"]] = $newValue;
                    }else{
                        $finalValues[$info["col"]] = $oldValue;

                        $memberName = $tUserId;
                        foreach($members as $m){
                            if($m["userId"] == $tUserId) $memberName = $m["Name"];
                        }

                        $blockedNotices[] = "$memberName - $reason";
                    }
                }

                $newMealRecordId = generatePkID("meal");
                $morningVal = $finalValues["Morning"];
                $lunchVal   = $finalValues["Lunch"];
                $dinnerVal  = $finalValues["Dinner"];

                $sqlQ = "INSERT INTO MealRecord (mealRecordId, messId, userId, Morning, Lunch, Dinner, mealDate, mealAddedBy)
                VALUES ('$newMealRecordId', '$messId', '$tUserId', '$morningVal', '$lunchVal', '$dinnerVal', '$mealDate', '$userId')
                ON DUPLICATE KEY UPDATE Morning='$morningVal', Lunch='$lunchVal', Dinner='$dinnerVal', mealAddedBy='$userId'";

                $result = exeQuery($sqlQ);

                if($result){
                    $anyRowSaved = true;
                }
            }

            if($anyRowSaved){
                $message = empty($blockedNotices)
                    ? "Meal Added/Updated Successfully!"
                    : "Meal Partially Updated. Some Meals Were Locked (Less Than 3 Hours Before Cook Time):";
            }else{
                $isErr = true;
                $errorMessage = "Failed To Save Meal!";
            }
        }
    }
}



$mealsSetForDate = array();

$sqlQ = "SELECT u.Name, mr.Morning, mr.Lunch, mr.Dinner, mr.updatedAt, mr.createdAt FROM MealRecord mr
INNER JOIN Users u ON mr.userId = u.userId
WHERE mr.messId='$messId' AND mr.mealDate='$mealDate'
ORDER BY mr.updatedAt DESC";
$result = exeQuery($sqlQ);

if($result){
    while($row = getDataRow($result)){
        $mealsSetForDate[] = $row;
    }
}



require_once __DIR__ . "/../../view/components/addMeal.php";

?>
