<?php
// Set page title and active menu for layout
$page_title = 'Admin Dashboard';
$active_menu = 'dashboard';

// In a real MVC setup, this data would come from the controller
// For now, we'll create some sample data
$stats = [
    'products' => [
        'count' => 47,
        'increase' => 8,
        'percent' => 20.5
    ],
    'feedback' => [
        'count' => 124,
        'increase' => 15,
        'percent' => 13.8
    ],
    'users' => [
        'count' => 856,
        'increase' => 42,
        'percent' => 5.2
    ],
    'orders' => [
        'count' => 235,
        'increase' => 28,
        'percent' => 12.9
    ]
];

// Sample recent feedback
$recentFeedback = [
    [
        'id' => 124,
        'name' => 'John Doe',
        'rating' => 5,
        'comment' => 'Great products and excellent service!',
        'date' => '2025-04-12',
        'status' => 'approved'
    ],
    [
        'id' => 123,
        'name' => 'Jane Smith',
        'rating' => 4,
        'comment' => 'Very good quality, but shipping took longer than expected.',
        'date' => '2025-04-11',
        'status' => 'approved'
    ],
    [
        'id' => 122,
        'name' => 'Alex Johnson',
        'rating' => 3,
        'comment' => 'Product is okay, but could be better.',
        'date' => '2025-04-10',
        'status' => 'pending'
    ]
];

// System info
$systemInfo = [
    'php_version' => PHP_VERSION,
    'server' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
    'database' => 'MySQL 8.0.25',
    'last_backup' => '2025-04-12 23:00:00'
];

// Start buffering for content
ob_start();
?>

<div class="container-fluid">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h3>
                        <i class="fas fa-smile-beam mr-2"></i>
                        Welcome, <?php echo htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?>!
                    </h3>
                    <p class="lead">Here's what's happening in your GreenMind store today.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Section -->
    <div class="row mb-4">
        <!-- Products Card -->
        <div class="col-md-3 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Products</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo number_format($stats['products']['count']); ?>
                            </div>
                            <?php if (isset($stats['products']['increase'])): ?>
                                <div class="text-xs text-success mt-2">
                                    <i class="fas fa-arrow-up"></i> 
                                    <?php echo $stats['products']['increase']; ?> new 
                                    (<?php echo $stats['products']['percent']; ?>%)
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-box fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Feedback Card -->
        <div class="col-md-3 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Feedback</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo number_format($stats['feedback']['count']); ?>
                            </div>
                            <?php if (isset($stats['feedback']['increase'])): ?>
                                <div class="text-xs text-success mt-2">
                                    <i class="fas fa-arrow-up"></i> 
                                    <?php echo $stats['feedback']['increase']; ?> new 
                                    (<?php echo $stats['feedback']['percent']; ?>%)
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users Card -->
        <div class="col-md-3 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Users</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo number_format($stats['users']['count']); ?>
                            </div>
                            <?php if (isset($stats['users']['increase'])): ?>
                                <div class="text-xs text-success mt-2">
                                    <i class="fas fa-arrow-up"></i> 
                                    <?php echo $stats['users']['increase']; ?> new 
                                    (<?php echo $stats['users']['percent']; ?>%)
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders Card -->
        <div class="col-md-3 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Orders</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo number_format($stats['orders']['count']); ?>
                            </div>
                            <?php if (isset($stats['orders']['increase'])): ?>
                                <div class="text-xs text-success mt-2">
                                    <i class="fas fa-arrow-up"></i> 
                                    <?php echo $stats['orders']['increase']; ?> new 
                                    (<?php echo $stats['orders']['percent']; ?>%)
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Feedback Section -->
        <div class="col-md-8 mb-4">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold">Recent Feedback</h6>
                </div>
                <div class="card-body">
                    <?php if (empty($recentFeedback)): ?>
                        <div class="text-center py-4">
                            <i class="fas fa-comment-slash fa-3x text-gray-300 mb-3"></i>
                            <p>No feedback available yet.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Rating</th>
                                        <th>Comment</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recentFeedback as $feedback): ?>
                                        <tr>
                                            <td><?php echo $feedback['id']; ?></td>
                                            <td><?php echo htmlspecialchars($feedback['name']); ?></td>
                                            <td>
                                                <?php 
                                                    for ($i = 1; $i <= 5; $i++) {
                                                        if ($i <= $feedback['rating']) {
                                                            echo '<i class="fas fa-star text-warning"></i>';
                                                        } else {
                                                            echo '<i class="far fa-star text-warning"></i>';
                                                        }
                                                    }
                                                ?>
                                            </td>
                                            <td><?php echo htmlspecialchars(substr($feedback['comment'], 0, 40) . (strlen($feedback['comment']) > 40 ? '...' : '')); ?></td>
                                            <td><?php echo $feedback['date']; ?></td>
                                            <td>
                                                <?php if ($feedback['status'] === 'approved'): ?>
                                                    <span class="badge badge-success">Approved</span>
                                                <?php elseif ($feedback['status'] === 'pending'): ?>
                                                    <span class="badge badge-warning">Pending</span>
                                                <?php else: ?>
                                                    <span class="badge badge-danger">Rejected</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-3">
                            <a href="/GreenMind/public/dashboard.php?route=feedback/admin" class="btn btn-primary btn-sm">
                                <i class="fas fa-comments mr-1"></i> View All Feedback
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Quick Actions Section -->
        <div class="col-md-4 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 mb-3">
                            <a href="/GreenMind/public/dashboard.php?route=products/admin/create" class="btn btn-success btn-block">
                                <i class="fas fa-plus-circle mr-1"></i> Add Product
                            </a>
                        </div>
                        <div class="col-6 mb-3">
                            <a href="/GreenMind/public/dashboard.php?route=blogs/admin/create" class="btn btn-info btn-block">
                                <i class="fas fa-blog mr-1"></i> New Blog Post
                            </a>
                        </div>
                        <div class="col-6 mb-3">
                            <a href="/GreenMind/public/dashboard.php?route=marketing" class="btn btn-warning btn-block">
                                <i class="fas fa-bullhorn mr-1"></i> Marketing
                            </a>
                        </div>
                        <div class="col-6 mb-3">
                            <a href="/GreenMind/public/dashboard.php?route=settings" class="btn btn-secondary btn-block">
                                <i class="fas fa-cog mr-1"></i> Settings
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- System Info Section -->
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold">System Information</h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <span class="font-weight-bold">PHP Version:</span>
                        <span class="float-right"><?php echo htmlspecialchars($systemInfo['php_version']); ?></span>
                    </div>
                    <div class="mb-2">
                        <span class="font-weight-bold">Web Server:</span>
                        <span class="float-right"><?php echo htmlspecialchars($systemInfo['server']); ?></span>
                    </div>
                    <div class="mb-2">
                        <span class="font-weight-bold">Database:</span>
                        <span class="float-right"><?php echo htmlspecialchars($systemInfo['database']); ?></span>
                    </div>
                    <div>
                        <span class="font-weight-bold">Last Backup:</span>
                        <span class="float-right"><?php echo htmlspecialchars($systemInfo['last_backup']); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Get the buffer and assign it to content
$content = ob_get_clean();

// Include layout
require_once dirname(__DIR__) . '/layout.php';
?>

