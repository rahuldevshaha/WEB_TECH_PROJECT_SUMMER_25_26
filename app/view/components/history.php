<link rel="stylesheet" href="/app/assets/css/home.css">

<div id="home_page">

    <div class="nav">
        <?php include __DIR__ . "/../layout/navbar.php"; ?>
    </div>

    <div class="body_section">

        <div class="sidebar_section">
            <?php include __DIR__ . "/../layout/sidebar.php"; ?>
        </div>

        <div class="content_section">

            <div id="content_body">

                <div class="no_history">
                    <h2>No History Found</h2>
                    <p>There is no mess history available yet.</p>
                </div>

            </div>

            <div class="footer_section">
                <?php include __DIR__ . "/../layout/footer.php"; ?>
            </div>

        </div>

    </div>

</div>


<style>
    .no_history {
        height: 400px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
    }

    .no_history h2 {
        margin-bottom: 8px;
        font-size: 24px;
        color: #333;
    }

    .no_history p {
        color: #888;
        font-size: 14px;
    }
</style>