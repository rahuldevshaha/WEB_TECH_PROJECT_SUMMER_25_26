


<html>
    <head>
        <title>Login Page</title>
        <link rel="stylesheet" href="../../assets/css/emailLogin.css">
    </head>
    <body >

    <div id="registration">
               <div class="img_section">
            <img src="../../assets/images/loginFormImg.webp">


        <div class="socials">
            <div class="socials_box">
                <img src="../../assets/images/FbPage.svg" alt="">
                <p>Our Facebook Page</p>
            </div>
            <div class="socials_box">
                <img src="../../assets/images/tutorial.svg" alt="">
                <p>How to use The App</p>
            </div>
            <div class="socials_box">
                <img src="../../assets/images/mobileApp.svg" alt="">
                <p>Mobile App</p>
            </div>

        </div>

        </div>
        <div class="form_section">


            
                <form method="post">
                    <div class="logo_wrapper">
                            <img src="../../assets/images/messManagerLogo.png" alt="logo">
                    </div>

                    <label for="email">Email</label>
                    <input type="text" name="email" value="" placeholder="Enter Email"><br>
                    <span class="textBoxBelowError"><?php echo $emailErr; ?></span><br>


                    <label for="name">Password</label>
                    <input type="password" name="password" value="" placeholder="Enter Password"  autocomplete="new-password"><br>
                    <span class="textBoxBelowError"><?php echo $passwordErr; ?></span><br>

                    
                    <a class="forget_pass" href="../forgotPassword.php">
                        Forgot Password?
                    </a>
                    

                    <span class="<?php echo empty($msg) ? '' : ($isErr ? 'errorStyle' : 'successStyle'); ?>">
                        <?php echo $msg; ?>
                    </span>

                    <input type="submit" value="Login">

                    <span>Not Have Account? <a href="../registration.php" class="sign_up_btn">Sign Up Here!</a></span>
                </form>
          
                
            
        </div>
    </div>
 



    <script src="../../assets/js/registration.js"></script>
    </body>
</html>