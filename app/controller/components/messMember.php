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



if(isset($_GET["check_email"])){

    header("Content-Type: application/json");

    $checkEmailVal = getValueFromReq("GET", "check_email");

    if($myRole != "Manager" || empty($checkEmailVal) || !checkEmail($checkEmailVal)){
        echo json_encode(array("found" => false));
        exit();
    }

    $sqlQ = "SELECT Name FROM Users WHERE Email='$checkEmailVal'";
    $result = exeQuery($sqlQ);

    if(getRowCount($result) > 0){
        $row = getDataRow($result);
        echo json_encode(array("found" => true, "name" => $row["Name"]));
    }else{
        echo json_encode(array("found" => false));
    }

    exit();
}



if(isset($_GET["search_members"])){

    header("Content-Type: application/json");

    $searchTerm = trim(getValueFromReq("GET", "search_members"));
    $likeTerm   = addslashes($searchTerm);

    $sqlQ = "SELECT u.userId, u.Name, u.Email, u.Phone, m.Role FROM Member m, Users u
    WHERE m.userId=u.userId AND m.messId='$messId'
    AND (u.Name LIKE '%$likeTerm%' OR u.Email LIKE '%$likeTerm%' OR u.Phone LIKE '%$likeTerm%')
    ORDER BY m.Role ASC, u.Name ASC";
    $result = exeQuery($sqlQ);

    $foundMembers = array();
    $todaySearchStr = date("Y-m-d");

    while($row = getDataRow($result)){

        
        $bazarSqlQ = "SELECT bazarDates FROM AssignBazar WHERE messId='$messId' AND userId='" . $row["userId"] . "'";
        $bazarResult = exeQuery($bazarSqlQ);

        $memberBazarDates = array();
        if(getRowCount($bazarResult) > 0){
            $bRow = getDataRow($bazarResult);
            $decoded = json_decode($bRow["bazarDates"], true);
            $memberBazarDates = is_array($decoded) ? $decoded : array();
        }

        $hasFutureDates = false;
        foreach($memberBazarDates as $bd){
            if($bd >= $todaySearchStr){
                $hasFutureDates = true;
                break;
            }
        }

        $foundMembers[] = array(
            "userId"         => $row["userId"],
            "name"           => $row["Name"],
            "email"          => $row["Email"],
            "phone"          => $row["Phone"],
            "role"           => $row["Role"],
            "bazarDates"     => array_values($memberBazarDates),
            "hasFutureDates" => $hasFutureDates
        );
    }

    echo json_encode(array(
        "members"   => $foundMembers,
        "isManager" => ($myRole == "Manager")
    ));

    exit();
}


$isErr = false;
$errorMessage = "";
$msg = "";

$memberName  = "";
$memberEmail = "";
$activeTab   = "memberList";


function ResetAllField(){
    global $memberName, $memberEmail, $isErr;

    $memberName  = "";
    $memberEmail = "";
    $isErr       = false;
}


if(reqMethodCheck("POST")){

    if(isset($_POST["add_member_submit"])){

        $activeTab = "addMember";

        if($myRole != "Manager"){
            $isErr = true;
            $errorMessage = "Only The Manager Can Add Members!";

        }else{

            $memberName  = getValueFromReq("POST", "member_name");
            $memberEmail = getValueFromReq("POST", "member_email");

            if(empty($memberEmail) || !checkEmail($memberEmail)){
                $isErr = true;
                $errorMessage = "Enter Valid Email!";

            }else{

                
                $sqlQ = "SELECT userId FROM Users WHERE Email='$memberEmail'";
                $result = exeQuery($sqlQ);

                if(getRowCount($result) > 0){

                    
                    $row = getDataRow($result);
                    $newUserId = $row["userId"];

                    
                    $sqlQ = "SELECT messId FROM Member WHERE userId='$newUserId'";
                    $result = exeQuery($sqlQ);

                    if(getRowCount($result) > 0){
                        $isErr = true;
                        $errorMessage = "This User Is Already In A Mess!";
                    }

                }else{

                    
                    if(empty($memberName) || !checkValidName($memberName, 3)){
                        $isErr = true;
                        $errorMessage = "Enter Valid Member Name!";

                    }else{

                        $newUserId       = generatePkID("uid");
                        $defaultPassword = hashPassword("12345");

                        $sqlQ = "INSERT INTO Users (userId, Email, Pass, Name) VALUES
                        ('$newUserId', '$memberEmail', '$defaultPassword', '$memberName')";

                        $result = exeQuery($sqlQ);

                        if(!$result){
                            $isErr = true;
                            $errorMessage = "Failed To Create Member Account!";
                        }
                    }
                }


                if(!$isErr){

                    $sqlQ = "INSERT INTO Member (messId, userId, Role, AddedBy) VALUES
                    ('$messId', '$newUserId', 'Member', '$userId')";

                    $result = exeQuery($sqlQ);

                    if($result){
                        $msg = "Member Added Successfully!";
                        ResetAllField();
                    }else{
                        $isErr = true;
                        $errorMessage = "Failed To Add Member To Mess!";
                    }
                }
            }
        }

    }else if(isset($_POST["remove_member_submit"])){

        if($myRole != "Manager"){
            $isErr = true;
            $errorMessage = "Only The Manager Can Remove Members!";
        }else{

            $deleteUserId = getValueFromReq("POST", "delete_user_id");

            if(empty($deleteUserId) || $deleteUserId == $userId){
                $isErr = true;
                $errorMessage = "Manager Cannot Remove Themselves!";
            }else{

                $sqlQ = "DELETE FROM Member WHERE messId='$messId' AND userId='$deleteUserId'";
                $result = exeQuery($sqlQ);

                if($result){
                    $msg = "Member Removed Successfully!";
                }else{
                    $isErr = true;
                    $errorMessage = "Failed To Remove Member!";
                }
            }
        }

    }else if(isset($_POST["assign_bazar_submit"])){

        
        if($myRole != "Manager"){
            $isErr = true;
            $errorMessage = "Only The Manager Can Assign Bazar Dates!";

        }else{

            $bazarUserId  = getValueFromReq("POST", "bazar_user_id");
            $bazarDatesRaw = getValueFromReq("POST", "bazar_dates");

            
            $sqlQ = "SELECT userId FROM Member WHERE messId='$messId' AND userId='$bazarUserId'";
            $result = exeQuery($sqlQ);

            if(empty($bazarUserId) || getRowCount($result) == 0){
                $isErr = true;
                $errorMessage = "Invalid Member Selected!";

            }else{

                $todayStr = date("Y-m-d");

                
                $datesDecoded = json_decode($bazarDatesRaw, true);
                $cleanDates = array();

                if(is_array($datesDecoded)){
                    foreach($datesDecoded as $d){
                        if(preg_match('/^\d{4}-\d{2}-\d{2}$/', $d)){
                            $parts = explode("-", $d);
                            if(checkdate((int)$parts[1], (int)$parts[2], (int)$parts[0])){
                                $cleanDates[$d] = true; 
                            }
                        }
                    }
                }

                
                $sqlQ = "SELECT bazarId, bazarDates FROM AssignBazar WHERE messId='$messId' AND userId='$bazarUserId'";
                $result = exeQuery($sqlQ);

                $existingBazarId = null;
                $existingDates = array();

                if(getRowCount($result) > 0){
                    $row = getDataRow($result);
                    $existingBazarId = $row["bazarId"];
                    $decodedExisting = json_decode($row["bazarDates"], true);
                    $existingDates = is_array($decodedExisting) ? $decodedExisting : array();
                }

                
                foreach($existingDates as $ed){
                    if($ed < $todayStr){
                        $cleanDates[$ed] = true;
                    }
                }

                
                $sqlQ = "SELECT bazarDates FROM AssignBazar WHERE messId='$messId' AND userId!='$bazarUserId'";
                $result = exeQuery($sqlQ);

                $takenByOthers = array();
                while($row = getDataRow($result)){
                    $decodedOther = json_decode($row["bazarDates"], true);
                    if(is_array($decodedOther)){
                        foreach($decodedOther as $od){
                            $takenByOthers[$od] = true;
                        }
                    }
                }

                $conflictFound = false;
                foreach(array_keys($cleanDates) as $cd){
                    if(isset($takenByOthers[$cd])){
                        unset($cleanDates[$cd]);
                        $conflictFound = true;
                    }
                }

                $cleanDates = array_keys($cleanDates);
                sort($cleanDates);

                $bazarDatesJson = addslashes(json_encode($cleanDates));

                if($existingBazarId){

                    $sqlQ = "UPDATE AssignBazar SET bazarDates='$bazarDatesJson', assignBy='$userId'
                    WHERE bazarId='$existingBazarId'";

                }else{
                    $newBazarId = generatePkID("bzr");

                    $sqlQ = "INSERT INTO AssignBazar (bazarId, messId, userId, assignBy, bazarDates) VALUES
                    ('$newBazarId', '$messId', '$bazarUserId', '$userId', '$bazarDatesJson')";
                }

                $result = exeQuery($sqlQ);

                if($result){
                    $msg = $conflictFound
                        ? "Bazar Dates Updated! (Some Selected Dates Were Already Assigned To Another Member, So Those Were Skipped)"
                        : "Bazar Dates Updated Successfully!";
                }else{
                    $isErr = true;
                    $errorMessage = "Failed To Assign Bazar Dates!";
                }
            }
        }

    }else if(isset($_POST["remove_bazar_submit"])){

        
        if($myRole != "Manager"){
            $isErr = true;
            $errorMessage = "Only The Manager Can Remove Bazar Dates!";
        }else{

            $bazarUserId = getValueFromReq("POST", "bazar_user_id");
            $todayStr = date("Y-m-d");

            $sqlQ = "SELECT bazarId, bazarDates FROM AssignBazar WHERE messId='$messId' AND userId='$bazarUserId'";
            $result = exeQuery($sqlQ);

            if(getRowCount($result) == 0){
                $isErr = true;
                $errorMessage = "No Bazar Dates Found For This Member!";

            }else{

                $row = getDataRow($result);
                $existingBazarId = $row["bazarId"];
                $decodedExisting = json_decode($row["bazarDates"], true);
                $existingDates = is_array($decodedExisting) ? $decodedExisting : array();

                
                $keptDates = array();
                foreach($existingDates as $ed){
                    if($ed < $todayStr){
                        $keptDates[] = $ed;
                    }
                }
                sort($keptDates);

                if(count($keptDates) == count($existingDates)){
                    $isErr = true;
                    $errorMessage = "Cannot Remove Past Assigned Bazar Dates!";

                }else{

                    if(count($keptDates) > 0){
                        $keptJson = addslashes(json_encode($keptDates));
                        $sqlQ = "UPDATE AssignBazar SET bazarDates='$keptJson' WHERE bazarId='$existingBazarId'";
                    }else{
                        $sqlQ = "DELETE FROM AssignBazar WHERE bazarId='$existingBazarId'";
                    }

                    $result = exeQuery($sqlQ);

                    if($result){
                        $msg = "Upcoming Bazar Dates Removed! (Past Dates Are Kept As History)";
                    }else{
                        $isErr = true;
                        $errorMessage = "Failed To Remove Bazar Dates!";
                    }
                }
            }
        }
    }
}



$members = array();

$sqlQ = "SELECT u.userId, u.Name, u.Email, u.Phone, m.Role FROM Member m, Users u
WHERE m.userId=u.userId AND m.messId='$messId' ORDER BY m.Role ASC, u.Name ASC";
$result = exeQuery($sqlQ);

while($row = getDataRow($result)){
    $members[] = $row;
}

$memberCount = count($members);



$todayStr = date("Y-m-d");



$bazarDatesMap = array();

$sqlQ = "SELECT userId, bazarDates FROM AssignBazar WHERE messId='$messId'";
$result = exeQuery($sqlQ);

while($row = getDataRow($result)){
    $decoded = json_decode($row["bazarDates"], true);
    $bazarDatesMap[$row["userId"]] = is_array($decoded) ? $decoded : array();
}



$userNameMap = array();
foreach($members as $mm){
    $userNameMap[$mm["userId"]] = $mm["Name"];
}

$allBazarAssignments = array();
foreach($bazarDatesMap as $uid => $dates){
    foreach($dates as $d){
        $allBazarAssignments[$d] = array(
            "userId" => $uid,
            "name"   => isset($userNameMap[$uid]) ? $userNameMap[$uid] : ""
        );
    }
}



require_once __DIR__ . "/../../view/components/messMember.php";

?>
