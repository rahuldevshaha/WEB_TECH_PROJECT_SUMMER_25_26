<?php

include_once "../utils/securityValidation.php";
ProtectedRequest("login/socialLogin.php");


$userId = getSessionValue("userId");

$isErr = false;
$errorMessage = "";
$msg = "";


if(reqMethodCheck("POST") && isset($_POST["update_profile_submit"])){

    $name  = trim(getValueFromReq("POST", "profile_name"));
    $phone = trim(getValueFromReq("POST", "profile_phone"));


    function isInvalidProfileInputs(){
        global $name, $phone;

        if(empty($name) || !checkValidName($name, 3)){
            return "Enter A Valid Name!";
        }

        if(!empty($phone) && !checkPhone($phone)){
            return "Enter A Valid Phone Number!";
        }

        return "";
    }

    $errorMessage = isInvalidProfileInputs();
    $isErr = !empty($errorMessage);


    
    $avatarSetClause = "";

    if(!$isErr && isset($_FILES["profile_avatar"]) && $_FILES["profile_avatar"]["error"] == UPLOAD_ERR_OK){

        $allowedExt = array("jpg", "jpeg", "png", "webp");
        $fileExt = strtolower(pathinfo($_FILES["profile_avatar"]["name"], PATHINFO_EXTENSION));

        if(!in_array($fileExt, $allowedExt)){
            $isErr = true;
            $errorMessage = "Only JPG, PNG Or WEBP Images Are Allowed For Profile Picture!";

        }else if($_FILES["profile_avatar"]["size"] > 2 * 1024 * 1024){
            $isErr = true;
            $errorMessage = "Profile Picture Must Be Smaller Than 2MB!";

        }else{

            $uploadDir = __DIR__ . "/../assets/uploads/avatars/";
            if(!is_dir($uploadDir)){
                mkdir($uploadDir, 0755, true);
            }

            $newFileName = $userId . "_" . time() . "." . $fileExt;
            $destPath = $uploadDir . $newFileName;

            if(move_uploaded_file($_FILES["profile_avatar"]["tmp_name"], $destPath)){
                $avatarPath = addslashes("/app/assets/uploads/avatars/" . $newFileName);
                $avatarSetClause = ", Avater='$avatarPath'";
            }else{
                $isErr = true;
                $errorMessage = "Failed To Upload Profile Picture!";
            }
        }
    }


    if(!$isErr){

        $nameEsc  = addslashes($name);
        $phoneEsc = addslashes($phone);
        $phoneSqlVal = empty($phone) ? "NULL" : "'$phoneEsc'";

        $sqlQ = "UPDATE Users SET Name='$nameEsc', Phone=$phoneSqlVal $avatarSetClause WHERE userId='$userId'";
        $result = exeQuery($sqlQ);

        if($result){
            $msg = "Profile Updated Successfully!";
        }else{
            $isErr = true;
            $errorMessage = "Failed To Update Profile!";
        }
    }
}



$sqlQ = "SELECT Name, Email, Phone, Avater FROM Users WHERE userId='$userId'";
$result = exeQuery($sqlQ);
$userRow = getDataRow($result);



require_once __DIR__ . "/../view/profile.php";

?>
