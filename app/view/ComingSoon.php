<link rel="stylesheet" href="/app/assets/css/home.css">

<style>
    #content_body {
        min-height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 30px;
        background: #f7f9fc;
    }

    .coming-soon-card {
        width: 100%;
        max-width: 500px;
        padding: 45px 35px;
        text-align: center;
        background: #ffffff;
        border: 1px solid #eeeeee;
        border-radius: 16px;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.04);
    }

    .coming-soon-icon {
        width: 60px;
        height: 60px;
        margin: 0 auto 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        background: #fff0f0;
        color: #ff5252;
    }

    .coming-soon-icon svg {
        width: 28px;
        height: 28px;
    }

    .coming-soon-title {
        margin: 0 0 8px;
        font-size: 24px;
        font-weight: 700;
        color: #374151;
    }

    .coming-soon-text {
        margin: 0;
        font-size: 14px;
        line-height: 1.6;
        color: #6b7280;
    }
</style>


<div id="home_page">

    <div class="nav">
        <?php include "layout/navbar.php"; ?>
    </div>

    <div class="body_section">

        <div class="sidebar_section">
            <?php include "layout/sidebar.php"; ?>
        </div>

        <div class="content_section">

            <div id="content_body">

                <div class="coming-soon-card">

                    <div class="coming-soon-icon">
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <rect x="3" y="4" width="18" height="18" rx="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                    </div>

                    <h2 class="coming-soon-title">
                        Coming Soon!
                    </h2>

                    <p class="coming-soon-text">
                        This feature is currently under development.
                        Please check back later.
                    </p>

                </div>

            </div>

            <div class="footer_section">
                <?php include "layout/footer.php"; ?>
            </div>

        </div>

    </div>

</div>