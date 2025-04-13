<?php include 'partials/sidebar.php' ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title ?? 'Dashboard') ?></title>
    <link href="http://<?= $_SERVER['HTTP_HOST'] ?>/2A27/view/assets/css/dashboard.css" rel="stylesheet">
    <script src="<?= base_url('view/assets/js/dashboard.js') ?>"></script>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar Navigation -->
        <aside class="sidebar">
        </aside>

        <!-- Main Content Area -->
        <main class="content-area">
            <!-- Top Header Bar -->
            <header class="top-header">
                <div class="header-left">
                    <h1><?= htmlspecialchars($page_title ?? 'Dashboard') ?></h1>
                </div>
                <div class="header-right">
                    <div class="user-menu">
                        <span class="user-avatar">👤</span>
                        <span class="user-name">Admin</span>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="page-content">
                <?= $content ?>
            </div>
        </main>
    </div>

</body>
</html>
