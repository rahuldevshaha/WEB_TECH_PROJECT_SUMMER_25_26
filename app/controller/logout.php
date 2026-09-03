<?php
    include_once "../utils/securityValidation.php";
    startSession();
    endSession();
    ProtectedRequest("login/socialLogin.php");
    exit;

?>
