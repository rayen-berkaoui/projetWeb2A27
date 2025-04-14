<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? htmlspecialchars($page_title) : 'GreenMind Admin'; ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <style>
        /* Sidebar Styles */
        .sidebar {
            min-height: calc(100vh - 56px);
            background-color: #343a40;
            color: #fff;
        }
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.75);
        }
        .sidebar .nav-link:hover {
            color: #fff;
        }
        .sidebar .nav-link.active {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        /* Main Layout Styles */
        .navbar-brand {
            font-weight: bold;
            color: #28a745 !important;
        }
        .content-wrapper {
            padding: 20px;
        }
        
        /* Card Styles */
        .card {
            margin-bottom: 1.5rem;
            border: none;
        }
        .card-header {
            background-color: #28a745;
            color: white;
            font-weight: bold;
            border-bottom: 0;
        }
        .card-body {
            padding: 1.25rem;
        }
        
        /* Card Border Colors */
        .border-left-success {
            border-left: 4px solid #28a745 !important;
        }
        .border-left-primary {
            border-left: 4px solid #007bff !important;
        }
        .border-left-info {
            border-left: 4px solid #17a2b8 !important;
        }
        .border-left-warning {
            border-left: 4px solid #ffc107 !important;
        }
        .border-left-danger {
            border-left: 4px solid #dc3545 !important;
        }
        
        /* Text Colors */
        .text-gray-800 {
            color: #5a5c69 !important;
        }
        .text-gray-300 {
            color: #dddfeb !important;
        }
        .text-xs {
            font-size: 0.7rem;
        }
        
        /* Shadow Effects */
        .shadow {
            box-shadow: 0 0.15rem 1.75rem rgba(0, 0, 0, 0.15) !important;
        }
        .shadow-sm {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
        }
        
        /* Card Hover Effects */
        .card:hover {
            box-shadow: 0 0.5rem 2rem rgba(0, 0, 0, 0.15) !important;
            transition: all 0.3s ease;
        }
        
        /* Responsive Fixes */
        @media (max-width: 768px) {
            .sidebar {
                min-height: auto;
                margin-bottom: 1rem;
            }
            .content-wrapper {
                padding: 10px;
            }
        }
        
        /* Utility Classes */
        .badge {
            padding: 0.4em 0.8em;
            font-size: 75%;
        }
        .btn-block {
            padding: 0.5rem 0.75rem;
        }
        .no-gutters {
            margin-right: 0;
            margin-left: 0;
        }
        .no-gutters > .col,
        .no-gutters > [class*="col-"] {
            padding-right: 0;
            padding-left: 0;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="/GreenMind/public/dashboard.php">
            <i class="fas fa-leaf mr-2"></i>GreenMind
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown">
                        <i class="fas fa-user-circle mr-1"></i>
                        <?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="/GreenMind/public/dashboard.php?route=settings">
                            <i class="fas fa-cog mr-2"></i>Settings
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="/GreenMind/public/login.php?logout=1">
                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 px-0 sidebar">
                <div class="list-group list-group-flush pt-3">
                    <a href="/GreenMind/public/dashboard.php" class="list-group-item list-group-item-action bg-dark text-white <?php echo $active_menu === 'dashboard' ? 'active' : ''; ?>">
                        <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                    </a>
                    <a href="/GreenMind/public/dashboard.php?route=products/admin" class="list-group-item list-group-item-action bg-dark text-white <?php echo $active_menu === 'products' ? 'active' : ''; ?>">
                        <i class="fas fa-box mr-2"></i>Products
                    </a>
                    <a href="/GreenMind/public/dashboard.php?route=feedback/admin" class="list-group-item list-group-item-action bg-dark text-white <?php echo $active_menu === 'avis' ? 'active' : ''; ?>">
                        <i class="fas fa-comments mr-2"></i>Feedback
                    </a>
                    <a href="/GreenMind/public/dashboard.php?route=blogs/admin" class="list-group-item list-group-item-action bg-dark text-white <?php echo $active_menu === 'blogs' ? 'active' : ''; ?>">
                        <i class="fas fa-blog mr-2"></i>Blog
                    </a>
                    <a href="/GreenMind/public/dashboard.php?route=users/admin" class="list-group-item list-group-item-action bg-dark text-white <?php echo $active_menu === 'users' ? 'active' : ''; ?>">
                        <i class="fas fa-users mr-2"></i>Users
                    </a>
                    <a href="/GreenMind/public/dashboard.php?route=marketing" class="list-group-item list-group-item-action bg-dark text-white <?php echo $active_menu === 'marketing' ? 'active' : ''; ?>">
                        <i class="fas fa-bullhorn mr-2"></i>Marketing
                    </a>
                    <a href="/GreenMind/public/dashboard.php?route=settings" class="list-group-item list-group-item-action bg-dark text-white <?php echo $active_menu === 'settings' ? 'active' : ''; ?>">
                        <i class="fas fa-cog mr-2"></i>Settings
                    </a>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 content-wrapper">
                <?php if (isset($_SESSION['success_message']) || isset($_SESSION['displayed_success_message'])): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle mr-2"></i>
                        <?php echo htmlspecialchars($_SESSION['success_message'] ?? $_SESSION['displayed_success_message']); ?>
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error_message']) || isset($_SESSION['displayed_error_message'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <?php echo htmlspecialchars($_SESSION['error_message'] ?? $_SESSION['displayed_error_message']); ?>
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                <?php endif; ?>

                <!-- Page Content -->
                <?php echo isset($content) ? $content : ''; ?>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Auto-dismiss alerts after 5 seconds
        $(document).ready(function() {
            setTimeout(function() {
                $('.alert').alert('close');
            }, 5000);
        });
    </script>
</body>
</html>
