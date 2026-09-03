
<?php

    include "utils/securityValidation.php";
    ProtectedRequest("controller/login/socialLogin.php");

    
    header("Location: controller/login/socialLogin.php");
    exit();


        
        


    

?>
