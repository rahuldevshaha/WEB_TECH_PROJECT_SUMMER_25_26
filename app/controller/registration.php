<?php 
   
    include_once "../utils/securityValidation.php";
    UnprotectedRequest("home.php");


    
    $uid= generatePkID("uid");
    $name ="";
    $email = "";
    $password = "";
    $phone = "";


    $isErr = false;
    $msg="";

    



    function ResetAllField(){
        global $uid, $name, $email, $password, $phone, $isErr;

        $uid = "";
        $name = "";
        $email = "";
        $password = "";
        $phone = "";
       
        $isErr = false;
    
    }

    

   if(reqMethodCheck("POST")){
       $name=getValueFromReq("POST", "name");
       $email=getValueFromReq("POST", "email");
       $password=getValueFromReq("POST", "password");
       $confirm_pass=getValueFromReq("POST", "confirm_pass");
       $phone=getValueFromReq("POST", "phone");


        function isInvalidInputs(){

            global $name, $email, $password, $confirm_pass, $phone, $dob, $isErr;

            if(empty($name) || !checkValidName($name, 3)){
                $isErr = true;
                return "Name Must be minimum 3 Char!";
            }
            if(empty($email) || !checkEmail($email)){
                    $isErr = true;
                    return "Enter Valid Email";
            }
            if(empty($phone) || !checkPhone($phone)){
                    $isErr = true;
                    return "Enter Valid Phone!";
            }
            if(empty($password) || !checkPassword($password, 6, false)){
                    $isErr = true;
                    return  "Password: min 6 chars, with letter, number & symbol.";
            }
            if ($password != $confirm_pass){
                    $isErr = true;
                    return "Does Not Match Password";
            }

                return "";

        }

        $msg=isInvalidInputs();
        

        


        if(!$isErr){

            $sqlQ = "SELECT * FROM Users WHERE Email='$email'";
            $result = exeQuery($sqlQ);
        
            if(getRowCount($result)< 1){
                
                $password =hashPassword($password);
                $sqlQ = "INSERT INTO Users (userId, Email, Pass, Name, Phone)VALUEs
                ('$uid', '$email', '$password', '$name', '$phone')";

                $result = exeQuery($sqlQ);

                if($result){
                    $isSuccess = true; 
                    
                    $msg="Registration Success!";
                    setSessionValue("regPageMsg", $msg);
                    
                    ResetAllField();

                    header("Location: login/emailLogin.php");
                    exit;
                }else{
                    $isErr = true;
                    $msg = "Failed To Create Account!";
                }
            }else{
                $isErr = true;
                $msg = "This Email is Associated with Other Account";
            }

        }
   }


    require_once __DIR__ . "/../view/registration.php";

?>




