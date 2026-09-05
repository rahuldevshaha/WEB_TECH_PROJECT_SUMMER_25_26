
<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <link rel="stylesheet" href="/app/view/assets/css/forgotPassword.css">
</head>
 
<body>
 
    <div class="top_bar"></div>
 
    <div id="forgotPassword">
        <div class="form_section">
            <form method="post">
 
                <div class="logo_wrapper">
                    <img src="/app/view/assets/images/messManagerLogo.png">
                </div>
 
                <div class="title">
                    Enter your email to reset your password<br>
                    and get an OTP
                </div>
 
                <label class="field_label">Your Email</label>
                <input type="text" name="email" placeholder="e.g. rahim@gmail.com" value="<?php echo $email; ?>">
                <span class="<?php echo empty($emailError) ? '' : 'errorStyle'; ?>"><?php echo $emailError; ?></span>
 
                <input type="submit" value="Get OTP">
 
                <span class="<?php echo empty($message) ? '' : 'successStyle'; ?>"><?php echo $message; ?></span>
 
                <div class="separation"><span>or</span></div>
 
                <a href="login/emailLogin.php">
                    <div class="login_btn">Go Back to Login.</div>
                </a>
 
            </form>
 
        </div>
    </div>
	

     <script src="/app/view/assets/js/forgotPassword.js"></script>

</body>
</html>


