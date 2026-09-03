

    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/app/assets/css/messSetting.css">
    <link rel="stylesheet" href="/app/assets/css/home.css">



    <div id="home_page">
        <div class="nav"><?php include __DIR__ . "/../layout/navbar.php"; ?></div>
        <div class="body_section">
            <div class="sidebar_section"> <?php include __DIR__ . "/../layout/sidebar.php";?></div>
            <div class="content_section">
                <div id="content_body">


                    <div class="settings-card">
                    <h2 class="form-title">Mess Settings</h2>

                    <?php if (!empty($errorMessage)): ?>
                        <p class="error-text"><?php echo $errorMessage; ?></p>
                    <?php endif; ?>

                    <?php if (!empty($msg)): ?>
                        <p class="success-text"><?php echo $msg; ?></p>
                    <?php endif; ?>

                    <form id="messSettingsForm" method="POST">
                        
                        <div class="form-group">
                            <label class="form-label" for="messName">মেসের নাম</label>
                            <input 
                                type="text" 
                                id="messName" 
                                name="mess_name" 
                                class="input-control" 
                                value="<?php echo $messName; ?>"
                                required
                            >
                        </div>

                        
                        <div class="form-group">
                            <label class="form-label" for="messCurrency">মেসের মুদ্রা</label>
                            <input 
                                type="text" 
                                id="messCurrency" 
                                name="mess_currency" 
                                class="input-control" 
                                value="<?php echo $messCurrency; ?>"
                                disabled
                                required
                            >
                        </div>

                        <div class="form-group">


                        <div class="transferBal">
                                                        <input
                                type="checkbox"
                                id="autoTranferBal"
                                name="autoTranferBal"
                                class="input-control"
                                <?php echo $autoTranferBal ? "checked" : ""; ?>
                            >
                            <label class="form-label" for="autoTranferBal">Auto Tranfer Balance to Next Month</label>
                        </div>

                        </div>



                        
                        <button type="submit" class="btn-submit">পরিবর্তন করুন</button>
                    </form>
                    </div>



                </div>
                <div class="footer_section"><?php include __DIR__ . "/../layout/footer.php";?></div>
            </div>
        </div>
    </div>






