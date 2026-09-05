<?php

    include_once  "../utils/securityValidation.php";
    UnprotectedRequest("home.php");


    if(checkSessionValidity("otp")||checkSessionValidity("resetEmail")){
        header("Location: forgotPassword.php");
        exit;
    }



    $otp = "";
    $newPassword = "";
    $confirmPassword = "";

    $otpError = "";
    $passwordError = "";
    $confirmError = "";
    $message = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $otp = $_POST["otp"];
        $newPassword = $_POST["newPassword"];
        $confirmPassword = $_POST["confirmPassword"];

        if (empty($otp)) {
            $otpError = "Enter OTP";
        } elseif ($otp != $_SESSION["otp"]) {
            $otpError = "Incorrect OTP";
        }

        if (empty($newPassword) || strlen($newPassword) < 6) {
            $passwordError = "Enter at least 6 characters";
        }

        if (empty($confirmPassword) || $confirmPassword !== $newPassword) {
            $confirmError = "Passwords do not match";
        }

        if ($otpError == "" && $passwordError == "" && $confirmError == "") {

            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $email = $_SESSION["resetEmail"];

            $update = "UPDATE Users SET Pass='$hashedPassword' WHERE Email='$email'";
            mysqli_query($conn, $update);

            unset($_SESSION["otp"]);
            unset($_SESSION["resetEmail"]);

            $message = "Password changed successfully!";
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <link rel="stylesheet" href="../view/assets/css/resetPassword.css">
</head>

<body>

    <div class="top_bar"></div>

    <div id="resetPassword">
        <div class="form_section">
            <form method="post">

                <div class="logo_wrapper">
                    <img src="../view/assets/images/messManagerLogo.png">
                </div>

                <div class="title">
                    Set New Password Using OTP
                </div>

                <label class="field_label">Enter 6-digit OTP</label>
                <input type="text" name="otp" placeholder="Enter 6-digit OTP" value="<?php echo $otp; ?>">
                <span class="<?php echo empty($otpError) ? '' : 'errorStyle'; ?>"><?php echo $otpError; ?></span>

                <label class="field_label">Enter New Password</label>
                <input type="password" name="newPassword" placeholder="Minimum 6 characters" autocomplete="new-password">
                <span class="<?php echo empty($passwordError) ? '' : 'errorStyle'; ?>"><?php echo $passwordError; ?></span>

                <label class="field_label">Confirm Password</label>
                <input type="password" name="confirmPassword" placeholder="Confirm Password" autocomplete="new-password">
                <span class="<?php echo empty($confirmError) ? '' : 'errorStyle'; ?>"><?php echo $confirmError; ?></span>

                <input type="submit" value="Set New Password">

                <span class="<?php echo empty($message) ? '' : 'successStyle'; ?>"><?php echo $message; ?></span>

                <div class="separation"><span>or</span></div>

                <a href="login/emailLogin.php">
                    <div class="login_btn">Go Back to Login.</div>
                </a>

            </form>

        </div>
    </div>
	<script src="../view/assets/js/resetPassword.js"></script>

</body>
</html>