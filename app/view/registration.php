


<html>
    <head>
        <title>Registration Page</title>
        <link rel="stylesheet" href="/app/view/assets/css/registration.css">
    </head>
    <body >

    <div id="registration">
               <div class="img_section">
            <img src="/app/view/assets/images/loginFormImg.webp">


        <div class="socials">
            <div class="socials_box">
                <img src="/app/view/assets/images/FbPage.svg" alt="">
                <p>Our Facebook Page</p>
            </div>
            <div class="socials_box">
                <img src="/app/view/assets/images/tutorial.svg" alt="">
                <p>How to use The App</p>
            </div>
            <div class="socials_box">
                <img src="/app/view/assets/images/mobileApp.svg" alt="">
                <p>Mobile App</p>
            </div>

        </div>

        </div>
        <div class="form_section">
                <form method="post">

                    <div class="logo_wrapper">
                            <img src="/app/view/assets/images/messManagerLogo.png" alt="logo">
                    </div>


                    <label for="name">Full Name</label>
                    <input type="text" name="name" placeholder="Enter Name"  value="<?php echo $name ?>"
                    ><br>

                    <label for="email">Email</label>
                    <input type="text" name="email"  placeholder="Enter Email" value="<?php echo $email ?>"
                    ><br>

                    <label for="phone">Phone</label>
                    <input type="text" name="phone" placeholder="Enter Phone" value="<?php echo $phone ?>"><br>

                    <label for="pass">Password</label>
                    <input type="password" name="password" placeholder="Enter Password" 
                    autocomplete="new-password" value="<?php echo $password ?>"><br>

                    <label for="confirm_pass">Confirm Password</label>
                    <input type="password" name="confirm_pass" placeholder="Retype Password" autocomplete="new-password"><br>

                   <span class="<?php echo empty($msg) ? '' : ($isErr ? 'errorStyle' : 'successStyle'); ?>">
                        <?php echo $msg; ?>
                    </span>

                    <input type="submit" value="Registration">

                    <div class="separation">
                        <span>or</span>
                    </div>


                    
                
                    <a href="login/socialLogin.php" class="login_btn">Already Have Account? Login</a>
                    

                </form>
          
                
            
        </div>
    </div>
 



    <script src="/app/view/assets/js/registration.js"></script>
    </body>
</html>