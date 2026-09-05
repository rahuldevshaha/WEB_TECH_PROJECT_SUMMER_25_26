<?php

include_once __DIR__ . "/../../utils/securityValidation.php";

$userId = getSessionValue("userId");
$userName = "User Name";

if(!empty($userId)){

    $sqlQ = "SELECT Name FROM Users WHERE userId='$userId'";
    $result = exeQuery($sqlQ);

    if(getRowCount($result) > 0){
        $row = getDataRow($result);
        $userName = $row["Name"];
    }
}

?>

<div class="navbar">
        <div class="logo_section">
            <div  class="logo_wrapper">
                <a href="/app/view/home.php">
                    <img src="/app/view/assets/images/messManagerLogo.png" class="nav-logo" alt="logo">
                </a>
            </div>
        </div>

        <div class="nav_items">

            <a href="/app/view/home.php" class="nav_item">
                <img src="/app/view/assets/images/home.svg" alt="">
            </a>
            <a href="/app/view/faq.php" class="nav_item">
                <img src="/app/view/assets/images/support.svg" alt="">
            </a>
            <a href="/app/view/notification.php" class="nav_item">
                <img src="/app/view/assets/images/notification.svg" alt="">
            </a>
            <a href="/app/controller/profile.php" class="nav_item">
                <img src="/app/view/assets/images/profile.svg" alt="">
            </a>
            <a href="/app/controller/components/messSetting.php" class="nav_item">
                <img src="/app/view/assets/images/settingRed.svg" alt="">
            </a>

        </div>


        <div class="profile_section">
    <p><?php echo htmlspecialchars($userName); ?></p>

    <div class="avater_wrapper">
        <a href="/app/controller/profile.php">
            <img src="/app/view/assets/images/defaultAvater.png" alt="avater">
        </a>
    </div>
</div>
    </div>

