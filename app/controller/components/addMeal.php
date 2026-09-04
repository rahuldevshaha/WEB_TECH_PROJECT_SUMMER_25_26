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


/* =========================================================
   CURRENT USER ROLE
========================================================= */

$sqlQ = "SELECT Role
         FROM Member
         WHERE messId='$messId'
         AND userId='$userId'";

$result = exeQuery($sqlQ);

$row = getDataRow($result);

$myRole = $row ? $row["Role"] : "";

$isManager = ($myRole == "Manager");


/* =========================================================
   MESS INFORMATION
========================================================= */

$sqlQ = "SELECT *
         FROM Messes
         WHERE messId='$messId'";

$result = exeQuery($sqlQ);

$messRow = getDataRow($result);


/* =========================================================
   ALL MEMBERS
========================================================= */

$members = array();

$sqlQ = "SELECT
            u.userId,
            u.Name,
            m.Role
         FROM Member m
         INNER JOIN Users u
            ON m.userId = u.userId
         WHERE m.messId='$messId'
         ORDER BY m.Role ASC, u.Name ASC";

$result = exeQuery($sqlQ);

while($row = getDataRow($result)){

    $members[] = $row;
}


/* =========================================================
   VARIABLES
========================================================= */

$isErr = false;

$errorMessage = "";

$message = "";

$blockedNotices = array();


/* =========================================================
   DEFAULT MEAL VALUES
========================================================= */

$breakfast =
    getSessionValue("mealDefault_breakfast");

$lunch =
    getSessionValue("mealDefault_lunch");

$dinner =
    getSessionValue("mealDefault_dinner");


$breakfast =
    ($breakfast === "")
    ? 0
    : floatval($breakfast);


$lunch =
    ($lunch === "")
    ? 0
    : floatval($lunch);


$dinner =
    ($dinner === "")
    ? 0
    : floatval($dinner);


/* =========================================================
   DEFAULT DATE
========================================================= */

$mealDate = date("Y-m-d");


/* =========================================================
   SET MEAL
========================================================= */

if(
    reqMethodCheck("POST") &&
    isset($_POST["set_meal"])
){

    $breakfast =
        isset($_POST["modal_breakfast"])
        ? 1
        : 0;


    $lunch =
        isset($_POST["modal_lunch"])
        ? 1
        : 0;


    $dinner =
        isset($_POST["modal_dinner"])
        ? 1
        : 0;


    setSessionValue(
        "mealDefault_breakfast",
        $breakfast
    );


    setSessionValue(
        "mealDefault_lunch",
        $lunch
    );


    setSessionValue(
        "mealDefault_dinner",
        $dinner
    );


    $message =
        "Meal Values Set! Now Select Members & Date To Apply.";


    /*
     * AJAX response
     */

    if(isset($_POST["ajax_set_meal"])){

        header(
            "Content-Type: application/json; charset=UTF-8"
        );


        echo json_encode(
            array(
                "success" => true,

                "breakfast" =>
                    $breakfast,

                "lunch" =>
                    $lunch,

                "dinner" =>
                    $dinner,

                "message" =>
                    $message
            )
        );


        exit();
    }
}


/* =========================================================
   ADD MEAL
========================================================= */

if(
    reqMethodCheck("POST") &&
    isset($_POST["add_meal_submit"])
){

    $memberSelect =
        getValueFromReq(
            "POST",
            "memberSelect"
        );


    $mealDate =
        getValueFromReq(
            "POST",
            "mealDate"
        );


    $reqBreakfast =
        getValueFromReq(
            "POST",
            "breakfast_val"
        );


    $reqLunch =
        getValueFromReq(
            "POST",
            "lunch_val"
        );


    $reqDinner =
        getValueFromReq(
            "POST",
            "dinner_val"
        );


    function isInvalidMealInputs(){

        global
            $mealDate,
            $reqBreakfast,
            $reqLunch,
            $reqDinner,
            $isErr;


        if(
            empty($mealDate) ||
            !preg_match(
                '/^\d{4}-\d{2}-\d{2}$/',
                $mealDate
            )
        ){

            $isErr = true;

            return "Select A Valid Meal Date!";
        }


        if(
            !checkIsNumber($reqBreakfast) ||
            !checkIsNumber($reqLunch) ||
            !checkIsNumber($reqDinner) ||
            $reqBreakfast < 0 ||
            $reqLunch < 0 ||
            $reqDinner < 0
        ){

            $isErr = true;

            return "Enter Valid Meal Numbers!";
        }


        return "";
    }


    $errorMessage =
        isInvalidMealInputs();


    if(!$isErr){

        $reqBreakfast =
            floatval($reqBreakfast);

        $reqLunch =
            floatval($reqLunch);

        $reqDinner =
            floatval($reqDinner);


        /* ===============================================
           TARGET MEMBERS
        =============================================== */

        $targetUserIds =
            array();


        if(
            $isManager &&
            $memberSelect === "all"
        ){

            foreach(
                $members
                as $m
            ){

                $targetUserIds[] =
                    $m["userId"];
            }

        }
        else if(
            $isManager &&
            !empty($memberSelect)
        ){

            $found = false;


            foreach(
                $members
                as $m
            ){

                if(
                    $m["userId"] ==
                    $memberSelect
                ){

                    $found = true;

                    break;
                }
            }


            if($found){

                $targetUserIds[] =
                    $memberSelect;

            }
            else{

                $isErr = true;

                $errorMessage =
                    "Selected Member Is Not Part Of This Mess!";
            }

        }
        else{

            $targetUserIds[] =
                $userId;
        }


        /* ===============================================
           SAVE MEAL
        =============================================== */

        if(!$isErr){

            $mealTypeMap = array(

                "Morning" => array(
                    "enabled" =>
                        $messRow["isMorningMeal"],

                    "value" =>
                        $reqBreakfast,

                    "col" =>
                        "Morning"
                ),

                "Lunch" => array(
                    "enabled" =>
                        $messRow["isLunchMeal"],

                    "value" =>
                        $reqLunch,

                    "col" =>
                        "Lunch"
                ),

                "Dinner" => array(
                    "enabled" =>
                        $messRow["isDinnerMeal"],

                    "value" =>
                        $reqDinner,

                    "col" =>
                        "Dinner"
                )
            );


            $anyRowSaved = false;


            foreach(
                $targetUserIds
                as $tUserId
            ){

                /* Existing record */

                $sqlQ = "
                    SELECT
                        mealRecordId,
                        Morning,
                        Lunch,
                        Dinner
                    FROM MealRecord
                    WHERE messId='$messId'
                    AND userId='$tUserId'
                    AND mealDate='$mealDate'
                ";


                $result =
                    exeQuery($sqlQ);


                $existing =
                    (
                        getRowCount($result) > 0
                    )
                    ? getDataRow($result)
                    : null;


                $finalValues =
                    array();


                foreach(
                    $mealTypeMap
                    as $mealType => $info
                ){

                    $oldValue =
                        $existing
                        ? floatval(
                            $existing[
                                $info["col"]
                            ]
                        )
                        : 0;


                    if(!$info["enabled"]){

                        $finalValues[
                            $info["col"]
                        ] = 0;

                        continue;
                    }


                    $newValue =
                        $info["value"];


                    if(
                        $newValue ==
                        $oldValue
                    ){

                        $finalValues[
                            $info["col"]
                        ] =
                            $oldValue;

                        continue;
                    }


                    list(
                        $allowed,
                        $reason
                    ) = canSetMeal(
                        $mealDate,
                        $mealType,
                        $messRow,
                        $isManager
                    );


                    if($allowed){

                        $finalValues[
                            $info["col"]
                        ] =
                            $newValue;

                    }
                    else{

                        $finalValues[
                            $info["col"]
                        ] =
                            $oldValue;


                        $memberName =
                            $tUserId;


                        foreach(
                            $members
                            as $m
                        ){

                            if(
                                $m["userId"] ==
                                $tUserId
                            ){

                                $memberName =
                                    $m["Name"];

                                break;
                            }
                        }


                        $blockedNotices[] =
                            "$memberName - $reason";
                    }
                }


                /* Insert / Update */

                $morningVal =
                    $finalValues["Morning"];


                $lunchVal =
                    $finalValues["Lunch"];


                $dinnerVal =
                    $finalValues["Dinner"];


                /*
                 * IMPORTANT:
                 * Do NOT rely on "INSERT ... ON DUPLICATE KEY
                 * UPDATE" here. That only updates the existing
                 * row if the table has a UNIQUE key covering
                 * (messId, userId, mealDate). mealRecordId is a
                 * randomly generated primary key, so without that
                 * extra unique constraint every submit silently
                 * INSERTs a brand new row instead of updating the
                 * existing one - causing duplicate rows and stale
                 * /incorrect meal counts ("meal thik moto update
                 * hoy na"). We already looked up $existing above,
                 * so explicitly UPDATE when it exists, otherwise
                 * INSERT a fresh row.
                 */

                if($existing){

                    $sqlQ = "
                        UPDATE MealRecord
                        SET
                            Morning='$morningVal',
                            Lunch='$lunchVal',
                            Dinner='$dinnerVal',
                            mealAddedBy='$userId'
                        WHERE mealRecordId='" .
                            $existing["mealRecordId"] .
                        "'
                    ";

                }
                else{

                    $newMealRecordId =
                        generatePkID("meal");


                    $sqlQ = "
                        INSERT INTO MealRecord
                        (
                            mealRecordId,
                            messId,
                            userId,
                            Morning,
                            Lunch,
                            Dinner,
                            mealDate,
                            mealAddedBy
                        )
                        VALUES
                        (
                            '$newMealRecordId',
                            '$messId',
                            '$tUserId',
                            '$morningVal',
                            '$lunchVal',
                            '$dinnerVal',
                            '$mealDate',
                            '$userId'
                        )
                    ";
                }


                $result =
                    exeQuery($sqlQ);


                if($result){

                    $anyRowSaved =
                        true;
                }
            }


            if($anyRowSaved){

                if(empty($blockedNotices)){

                    $message =
                        "Meal Added/Updated Successfully!";

                }
                else{

                    $message =
                        "Meal Partially Updated. Some Meals Were Locked (Less Than 3 Hours Before Cook Time):";
                }

            }
            else{

                $isErr = true;

                $errorMessage =
                    "Failed To Save Meal!";
            }
        }
    }
}


/* =========================================================
   PREFILL MEAL TOGGLE VALUES FROM ACTUAL SAVED MEAL
   IMPORTANT: $breakfast/$lunch/$dinner above only reflect a
   generic session "default" (last used in the Set Meal
   modal). That is NOT the same thing as what is actually
   booked for a member on the selected date. e.g. When a
   Manager sets/updates meal for all members, each normal
   member's own session default is untouched, so their Edit
   icon kept showing everything OFF even though the manager
   had already booked their meal. Fix: whenever we know a
   specific target member (a normal member editing their own
   meal, or a manager who selected one specific member),
   look up the real MealRecord for that member + the chosen
   date and use it to override the toggle/hidden values.
========================================================= */

$prefillTargetUserId =
    $isManager
    ? (
        isset($_POST["memberSelect"]) &&
        $_POST["memberSelect"] !== "all" &&
        $_POST["memberSelect"] !== ""
        ? $_POST["memberSelect"]
        : ""
    )
    : $userId;


if(!empty($prefillTargetUserId)){

    $safePrefillUserId =
        addslashes($prefillTargetUserId);

    $sqlQ = "
        SELECT Morning, Lunch, Dinner
        FROM MealRecord
        WHERE messId='$messId'
        AND userId='$safePrefillUserId'
        AND mealDate='$mealDate'
    ";

    $result = exeQuery($sqlQ);

    if($result && getRowCount($result) > 0){

        $existingForPrefill =
            getDataRow($result);

        $breakfast =
            floatval($existingForPrefill["Morning"]);

        $lunch =
            floatval($existingForPrefill["Lunch"]);

        $dinner =
            floatval($existingForPrefill["Dinner"]);
    }
}


/* =========================================================
   ACTIVE TAB
   ADD MEAL POST = ALWAYS ADD TAB
========================================================= */

if(
    reqMethodCheck("POST") &&
    isset($_POST["add_meal_submit"])
){

    $activeMealTab =
        "add";

}
else if(
    isset($_GET["mealTab"]) &&
    $_GET["mealTab"] === "booked"
){

    $activeMealTab =
        "booked";

}
else{

    $activeMealTab =
        "add";
}


/* =========================================================
   TODAY'S MEALS
   IMPORTANT: This table must ALWAYS show today's date,
   not the date selected in the Add Meal form.
========================================================= */

$todayMealDate = date("Y-m-d");

$mealsSetForDate =
    array();


$sqlQ = "
    SELECT
        u.userId,
        u.Name,

        COALESCE(
            mr.Morning,
            0
        ) AS Morning,

        COALESCE(
            mr.Lunch,
            0
        ) AS Lunch,

        COALESCE(
            mr.Dinner,
            0
        ) AS Dinner

    FROM Users u

    INNER JOIN Member m
        ON m.userId=u.userId
        AND m.messId='$messId'

    LEFT JOIN MealRecord mr
        ON mr.userId=u.userId
        AND mr.messId='$messId'
        AND mr.mealDate='$todayMealDate'

    ORDER BY
        m.Role ASC,
        u.Name ASC
";


$result =
    exeQuery($sqlQ);


if($result){

    while(
        $row = getDataRow($result)
    ){

        $mealsSetForDate[] =
            $row;
    }
}


/* =========================================================
   BOOKED MEAL FILTER
========================================================= */

$bookedDateFilter =
    isset($_GET["bookedDate"])
    ? $_GET["bookedDate"]
    : "";


$bookedPersonFilter =
    isset($_GET["bookedPerson"])
    ? $_GET["bookedPerson"]
    : "";


if(
    $bookedDateFilter === null
){

    $bookedDateFilter = "";
}


if(
    $bookedPersonFilter === null
){

    $bookedPersonFilter = "";
}


/* =========================================================
   ALREADY BOOKED MEALS
========================================================= */

$alreadyBookedMeals =
    array();


/*
 * IMPORTANT: A Manager can browse everyone's booked meals
 * (optionally filtered by date/member). A normal member
 * could not see this tab's table at all before - fix by
 * always running the query, but scoping a normal member's
 * results to their own userId only (privacy + it matches
 * how the Add Meal tab already limits normal members to
 * "Only You").
 */

$where =
    "mr.messId='$messId'";


/* Date filter (available to everyone) */

if(
    !empty($bookedDateFilter) &&
    preg_match(
        '/^\d{4}-\d{2}-\d{2}$/',
        $bookedDateFilter
    )
){

    $where .=
        " AND mr.mealDate='$bookedDateFilter'";
}


if($isManager){

    /* Member filter - manager can pick anyone */

    if(
        !empty($bookedPersonFilter)
    ){

        $safePerson =
            addslashes(
                $bookedPersonFilter
            );


        $where .=
            " AND mr.userId='$safePerson'";
    }

}
else{

    /* Normal member can only ever see their own meals */

    $safePerson =
        addslashes($userId);


    $where .=
        " AND mr.userId='$safePerson'";


    $bookedPersonFilter =
        $userId;
}


$sqlQ = "
    SELECT
        mr.mealDate,
        mr.userId,
        u.Name,
        mr.Morning,
        mr.Lunch,
        mr.Dinner

    FROM MealRecord mr

    INNER JOIN Users u
        ON u.userId=mr.userId

    WHERE $where

    ORDER BY
        mr.mealDate DESC,
        u.Name ASC
";


$result =
    exeQuery($sqlQ);


if($result){

    while(
        $row = getDataRow($result)
    ){

        $alreadyBookedMeals[] =
            $row;
    }
}


/* =========================================================
   LOAD VIEW
========================================================= */

require_once __DIR__ .
    "/../../view/components/addMeal.php";

?>