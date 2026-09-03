<html>
    <head>
        <title>Dashboard Page</title>
        <link rel="stylesheet" href="../assets/css/home.css">
        <link rel="stylesheet" href="../assets/css/layoutFile.css">


    </head>
    <body>




    
    <div id="home_page">
        <div class="nav"><?php include "layout/navbar.php"; ?></div>
        <div class="body_section">
            <div class="sidebar_section"> <?php include "layout/sidebar.php";?></div>
            <div class="content_section">
                <div id="content_body">


                    <?php include "components/dashboard.php";?>


                </div>
                <div class="footer_section"><?php include "layout/footer.php";?></div>
            </div>
        </div>
    </div>





    <script src="../assets/js/home.js"></script>
    </body>
</html>