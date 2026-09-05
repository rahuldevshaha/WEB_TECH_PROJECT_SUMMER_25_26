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

    if(empty($name) || !checkValidName($name, 3)){
        $isErr = true;
        $errorMessage = "Enter A Valid Name!";
    } else if(!empty($phone) && !checkPhone($phone)){
        $isErr = true;
        $errorMessage = "Enter A Valid Phone Number!";
    }

    $avatarSetClause = "";

    if(!$isErr && isset($_FILES["profile_avatar"]) && $_FILES["profile_avatar"]["error"] == UPLOAD_ERR_OK){

        $allowedExt = array("jpg", "jpeg", "png", "webp");
        $fileExt = strtolower(pathinfo($_FILES["profile_avatar"]["name"], PATHINFO_EXTENSION));

        if(!in_array($fileExt, $allowedExt)){
            $isErr = true;
            $errorMessage = "Only JPG, PNG Or WEBP Images Are Allowed For Profile Picture!";
        } else if($_FILES["profile_avatar"]["size"] > 2 * 1024 * 1024){
            $isErr = true;
            $errorMessage = "Profile Picture Must Be Smaller Than 2MB!";
        } else {
            $uploadDir = __DIR__ . "/../assets/uploads/avatars/";
            if(!is_dir($uploadDir)){
                mkdir($uploadDir, 0755, true);
            }

            $newFileName = $userId . "_" . time() . "." . $fileExt;
            $destPath = $uploadDir . $newFileName;

            if(move_uploaded_file($_FILES["profile_avatar"]["tmp_name"], $destPath)){
                $avatarPath = addslashes("/app/view/assets/uploads/avatars/" . $newFileName);
                $avatarSetClause = ", Avater='$avatarPath'";
            } else {
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
        } else {
            $isErr = true;
            $errorMessage = "Failed To Update Profile!";
        }
    }
}




if(reqMethodCheck("POST") && isset($_POST["update_email_submit"])){
    $newEmail = trim(getValueFromReq("POST", "new_email"));
    $oldPass  = getValueFromReq("POST", "old_password_email");

    if(empty($newEmail) || !filter_var($newEmail, FILTER_VALIDATE_EMAIL)){
        $isErr = true;
        $errorMessage = "Please enter a valid email address!";
    } else if(empty($oldPass)){
        $isErr = true;
        $errorMessage = "Current password is required to change email!";
    } else {
        $sqlPass = "SELECT Pass FROM Users WHERE userId='$userId'";
        $passRes = exeQuery($sqlPass);
        $passRow = getDataRow($passRes);

        $storedPass = $passRow["Pass"] ?? "";
        $isPassCorrect = (function_exists('password_verify') && password_verify($oldPass, $storedPass)) || ($oldPass === $storedPass);

        if(!$isPassCorrect){
            $isErr = true;
            $errorMessage = "Incorrect current password!";
        } else {
            $newEmailEsc = addslashes($newEmail);
            $checkEmailQ = "SELECT userId FROM Users WHERE Email='$newEmailEsc' AND userId != '$userId'";
            $checkRes = exeQuery($checkEmailQ);

            if(getDataRow($checkRes)){
                $isErr = true;
                $errorMessage = "This email is already associated with another account!";
            } else {
                $updateEmailQ = "UPDATE Users SET Email='$newEmailEsc' WHERE userId='$userId'";
                if(exeQuery($updateEmailQ)){
                    $msg = "Email updated successfully!";
                } else {
                    $isErr = true;
                    $errorMessage = "Failed to update email!";
                }
            }
        }
    }
}




if(reqMethodCheck("POST") && isset($_POST["update_password_submit"])){
    $oldPass = getValueFromReq("POST", "old_password");
    $newPass = getValueFromReq("POST", "new_password");

    if(empty($oldPass)){
        $isErr = true;
        $errorMessage = "Current password is required!";
    } else if(empty($newPass) || strlen($newPass) < 6){
        $isErr = true;
        $errorMessage = "New password must be at least 6 characters long!";
    } else {
        $sqlPass = "SELECT Pass FROM Users WHERE userId='$userId'";
        $passRes = exeQuery($sqlPass);
        $passRow = getDataRow($passRes);

        $storedPass = $passRow["Pass"] ?? "";
        $isPassCorrect = (function_exists('password_verify') && password_verify($oldPass, $storedPass)) || ($oldPass === $storedPass);

        if(!$isPassCorrect){
            $isErr = true;
            $errorMessage = "Incorrect current password!";
        } else {
            $newHashedPass = function_exists('password_hash') ? password_hash($newPass, PASSWORD_BCRYPT) : $newPass;
            $newHashedPassEsc = addslashes($newHashedPass);

            $updatePassQ = "UPDATE Users SET Pass='$newHashedPassEsc' WHERE userId='$userId'";
            if(exeQuery($updatePassQ)){
                $msg = "Password updated successfully!";
            } else {
                $isErr = true;
                $errorMessage = "Failed to update password!";
            }
        }
    }
}


$sqlQ = "SELECT Name, Email, Phone, Avater FROM Users WHERE userId='$userId'";
$result = exeQuery($sqlQ);
$userRow = getDataRow($result);

require_once __DIR__ . "/../view/profile.php";