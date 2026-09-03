<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    include_once __DIR__ . "/../utils/securityValidation.php";
    ProtectedRequest("/app/controller/login/socialLogin.php");

    $notifications = [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <link rel="stylesheet" href="/app/assets/css/layoutFile.css">
    <style>
        body {
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        .fullpage-notification-body {
            width: 100%;
            min-height: calc(100vh - 75px);
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding-top: 50px;
        }

        .no-notification-text {
            font-size: 15px;
            color: #374151;
            font-weight: 400;
            letter-spacing: 0.2px;
        }

        .notification-list-box {
            width: 90%;
            max-width: 600px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .notification-card {
            background: #ffffff;
            padding: 14px 18px;
            border-radius: 8px;
            border-left: 4px solid #ef4444;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
            font-size: 14px;
            color: #374151;
        }
    </style>
</head>
<body>

   
    <?php include __DIR__ . "/layout/navbar.php"; ?>

  
    <main class="fullpage-notification-body">
        <?php if (empty($notifications)): ?>
            <p class="no-notification-text">No Notification Found!</p>
        <?php else: ?>
            <div class="notification-list-box">
                <?php foreach ($notifications as $item): ?>
                    <div class="notification-card">
                        <p><?php echo htmlspecialchars($item); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

</body>
</html>