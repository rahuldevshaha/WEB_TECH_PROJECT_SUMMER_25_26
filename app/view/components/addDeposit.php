
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/app/assets/css/addDeposit.css">
    <link rel="stylesheet" href="/app/assets/css/home.css">




    <div id="home_page">
        <div class="nav"><?php include __DIR__ . "/../layout/navbar.php"; ?></div>
        <div class="body_section">
            <div class="sidebar_section"> <?php include __DIR__ . "/../layout/sidebar.php";?></div>
            <div class="content_section">
                <div id="content_body">


                    
    <div class="deposit-card">
        <h2 class="form-title">When a member deposits money to the mess, add it from this option</h2>

        <?php if (!empty($errorMessage)): ?>
            <p class="error-text"><?php echo $errorMessage; ?></p>
        <?php endif; ?>

        <?php if (!empty($msg)): ?>
            <p class="success-text"><?php echo $msg; ?></p>
        <?php endif; ?>

        <form method="POST">

        
            
            <div class="form-group">
                <label class="form-label" for="deposit_date">Deposit Date</label>
                <div class="input-wrapper">
                    <input type="date" id="deposit_date" name="deposit_date" class="input-control" value="<?php echo $depositDate; ?>" required>
                </div>
            </div> 


            
            <div class="form-group">
                <label class="form-label" for="amount">Amount</label>
                <input type="text" id="amount" name="amount" class="input-control" placeholder="500 or -500" value="<?php echo $amount; ?>" required>
            </div>

            
            <div class="form-group">
                <label class="form-label" for="note">Deposit Note (optional)</label>
                <textarea id="note" name="note" class="input-control" placeholder="e.g. House rent, meal money"><?php echo $note; ?></textarea>
            </div>

            
            <div class="form-group">
                <label class="form-label" for="member">Select who has deposited</label>
                <div class="select-wrapper">
                    <select id="member" name="member" class="input-control" required>
                        <option value="" disabled <?php echo empty($member) ? "selected" : ""; ?> hidden>Select who has deposited</option>
                        <?php foreach ($members as $m): ?>
                            <option value="<?php echo $m['userId']; ?>" <?php echo ($member === $m['userId']) ? "selected" : ""; ?>>
                                <?php echo $m['Name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    
                    <div class="select-arrow">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#e2e8f0" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </div>
                </div>
            </div>


            <button type="submit" class="btn-submit">Add Deposit</button>
        </form>
    </div>


                </div>
                <div class="footer_section"><?php include __DIR__ . "/../layout/footer.php";?></div>
            </div>
        </div>
    </div>



<script src="/app/assets/js/addDeposit.js"></script>



