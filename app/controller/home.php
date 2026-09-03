<?php
    include_once "../utils/securityValidation.php";
    ProtectedRequest("login/socialLogin.php");



    
    
    require_once __DIR__ . "/../view/home.php";

?>




