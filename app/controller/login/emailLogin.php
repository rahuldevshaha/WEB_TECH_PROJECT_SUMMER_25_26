<?php 
   
    include_once "../../utils/securityValidation.php";
    UnprotectedRequest("../home.php");

    

    

    $email="";
    $password= "";

    $isErr =false;
    $emailErr="";
    $passwordErr= "";
    $msg= getSessionValue("regPageMsg");


    function ResetAllField(){
        global  $email, $password, $isErr;

        
        $email = "";
        $password = "";
        
        $isErr = false;
        $emailErr="";
        $passwordErr= "";
}



     if(reqMethodCheck("POST")){
       
    
       $email=getValueFromReq("POST", "email");
       $password=getValueFromReq("POST", "password");

       

        if(empty($email) || !checkEmail($email)){
                $isErr = true;
                $emailErr= "Enter Valid Email";
        }
        if(empty($password) || !checkPassword($password, 6, false)){
                $isErr = true;
                $passwordErr=  "Enter Valid Password.";
        }

   

        


        if(!$isErr){

            $sqlQ = "SELECT userId, Email, Pass FROM Users WHERE Email='$email'";
            $result = exeQuery($sqlQ);
        
            if(getRowCount($result) == 1){

                $row= getDataRow($result);

                $isUserVerified= password_verify($password, $row["Pass"]);



                if($isUserVerified){
                    $isSuccess = true; 
                    setSessionValue("userId", $row["userId"]);
                    $msg="Login Success!";
                    setSessionValue("LoginPageMsg", $msg);
                    
                    ResetAllField();
                    header("Location: ../home.php");
                    exit;
                }else{
                    $isErr = true;
                    $msg = "Email or Password Incorrect!";
                }
            }else{
                $isErr = true;
                $msg = "No User Found. Sign Up First!";
            }

        }



}


require_once __DIR__ . "/../../view/login/emailLogin.php";


?>





