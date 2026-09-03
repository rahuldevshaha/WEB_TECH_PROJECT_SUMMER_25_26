<?php


    include_once "../utils/securityValidation.php";
    UnprotectedRequest("home.php");


    $email = "";
    $emailError = "";
    $message = "";

    if (reqMethodCheck("POST")) {


        $email =getValueFromReq("POST","email");


        if (empty($email)) {
            $emailError = "Enter Email";
        }

        if ($emailError == "") {

            $check = "SELECT * FROM Users WHERE Email='$email'";
            $result = exeQuery($check);

            if (getRowCount($result) > 0) {

                $otp = rand(100000, 999999);

                setSessionValue("otp", $otp);
                setSessionValue("resetEmail", $email);


                $message = "OTP Sent To Your Email";

            } else {
                $emailError = "Email Not Found";
            }
        }
    }



require __DIR__ . "/../view/forgotPassword.php";
?>


