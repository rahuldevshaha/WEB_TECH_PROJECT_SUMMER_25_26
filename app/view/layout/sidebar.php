<link rel="stylesheet" href="/app/assets/css/layoutFile.css">
 <script src="/app/assets/js/loader.js"></script>

<?php
   
    $currentSidebarPage = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

   
    function sidebarActiveClass($href, $currentSidebarPage) {
        return basename($href) === $currentSidebarPage ? ' active' : '';
    }
?>

<div class="sidebar">

    <div class="sidebar_items">


<a href="/app/view/home.php" class="sidebar_item<?php echo sidebarActiveClass('/app/view/home.php', $currentSidebarPage); ?>">
    <img src="/app/assets/images/homeSidebar.svg" alt="icon">
    <p>Home</p>
</a>

<a href="/app/controller/components/createMess.php" class="sidebar_item<?php echo sidebarActiveClass('/app/controller/components/createMess.php', $currentSidebarPage); ?>">
    <img src="/app/assets/images/Create.svg" alt="icon">
    <p>Create Mess</p>
</a>

<a href="/app/controller/components/addDeposit.php" class="sidebar_item<?php echo sidebarActiveClass('/app/controller/components/addDeposit.php', $currentSidebarPage); ?>">
    <img src="/app/assets/images/AddDeposit.svg" alt="icon">
    <p>Add Deposit</p>
</a>

<a href="/app/controller/components/addMeal.php" class="sidebar_item<?php echo sidebarActiveClass('/app/controller/components/addMeal.php', $currentSidebarPage); ?>">
    <img src="/app/assets/images/AddMeal.svg" alt="icon">
    <p>Add meal</p>
</a>

<a href="/app/controller/components/addCost.php" class="sidebar_item<?php echo sidebarActiveClass('/app/controller/components/addCost.php', $currentSidebarPage); ?>">
    <img src="/app/assets/images/AddCost.svg" alt="icon">
    <p>Add Cost</p>
</a>

<a href="/app/view/components/activeMonthDetails.php" class="sidebar_item<?php echo sidebarActiveClass('/app/view/components/activeMonthDetails.php', $currentSidebarPage); ?>">
    <img src="/app/assets/images/ActiveMonthDetails.svg" alt="icon">
    <p>Active Month Details</p>
</a>











<a href="/app/controller/components/messMember.php" class="sidebar_item<?php echo sidebarActiveClass('/app/controller/components/messMember.php', $currentSidebarPage); ?>">
    <img src="/app/assets/images/MessMembers.svg" alt="icon">
    <p>Mess Members</p>
</a>

<a href="/app/controller/components/ChangeMessManager.php" class="sidebar_item<?php echo sidebarActiveClass('/app/controller/components/ChangeMessManager.php', $currentSidebarPage); ?>">
    <img src="/app/assets/images/ChangeManager.svg" alt="icon">
    <p>Change manager</p>
</a>

<a href="/app/controller/components/messSetting.php" class="sidebar_item<?php echo sidebarActiveClass('/app/controller/components/messSetting.php', $currentSidebarPage); ?>">
    <img src="/app/assets/images/setting.svg" alt="icon">
    <p>Mess Setting</p>
</a>

<a href="/app/controller/components/deleteMess.php" class="sidebar_item<?php echo sidebarActiveClass('/app/controller/components/deleteMess.php', $currentSidebarPage); ?>">
    <img src="/app/assets/images/deleteBin.svg" alt="icon">
    <p>Delete Mess</p>
</a>

<a href="/app/controller/components/history.php" class="sidebar_item">
    <img src="/app/assets/images/historySidebar.svg" alt="icon">
    <p>History</p>
</a>

    </div>



    <div>
        <a href="/app/controller/logout.php" class="logout_btn_section">
            Logout
            <img src="/app/assets/images/LOGOUT.svg" alt="icon">
        </a>
    </div>

</div>