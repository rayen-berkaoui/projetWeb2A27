<?php
// Enable error reporting for development
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start session with security settings
session_start([
    'cookie_httponly' => true,     // Prevent JavaScript access to session cookie
    'cookie_secure' => false,      // Set to true in production with HTTPS
    'cookie_samesite' => 'Strict', // Prevent CSRF
    'use_strict_mode' => true      // Use strict session mode
]);

// Function to log errors
function logError($message, $type = 'ERROR') {
    error_log("[$type] Dashboard: $message");
    
    // For debugging, also output to browser if we're in development mode
    if (ini_get('display_errors')) {
        // Only output debug info for certain types
        if (in_array($type, ['DEBUG', 'INFO'])) {
            echo "<!-- [{$type}] {$message} -->\n";
        }
    }
}

// Security: Handle session message cleanup - prevent accumulation
if (isset($_SESSION['success_message']) && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $successMessage = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
    $_SESSION['displayed_success_message'] = $successMessage;
} elseif (isset($_SESSION['displayed_success_message']) && $_SERVER['REQUEST_METHOD'] !== 'GET') {
    unset($_SESSION['displayed_success_message']);
}

if (isset($_SESSION['error_message']) && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $errorMessage = $_SESSION['error_message'];
    unset($_SESSION['error_message']);
    $_SESSION['displayed_error_message'] = $errorMessage;
} elseif (isset($_SESSION['displayed_error_message']) && $_SERVER['REQUEST_METHOD'] !== 'GET') {
    unset($_SESSION['displayed_error_message']);
}

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Base configuration
define('BASE_PATH', dirname(__DIR__));
define('VIEW_PATH', BASE_PATH . '/app/view');
define('CONTROLLER_PATH', BASE_PATH . '/app/controller');

// Get the requested route
$route = $_GET['route'] ?? 'dashboard';
$action = $_GET['action'] ?? null;
$id = $_GET['id'] ?? null;

// Special case for old "avis" route - redirect to new path
if ($route === 'avis') {
    header('Location: dashboard.php?route=feedback/admin');
    exit;
}

// Ensure required directories exist
$requiredDirs = [
    CONTROLLER_PATH => 'Controller',
    VIEW_PATH => 'View',
    BASE_PATH . '/app/model' => 'Model'
];

foreach ($requiredDirs as $dir => $name) {
    if (!is_dir($dir)) {
        try {
            if (!mkdir($dir, 0755, true)) {
                throw new Exception("Failed to create $name directory");
            }
            logError("Created missing $name directory: $dir", 'INFO');
        } catch (Exception $e) {
            logError($e->getMessage());
            die("Error: Could not create required $name directory. Please check permissions.");
        }
    }
}

// Include required controllers
try {
    // Always include AuthController
    $authControllerPath = CONTROLLER_PATH . '/AuthController.php';
    if (!file_exists($authControllerPath)) {
        throw new Exception("AuthController.php not found");
    }
    require_once $authControllerPath;
    
    // Include FeedbackController for feedback routes
    if (strpos($route, 'feedbacks') === 0 || $route === 'avis') {
        $feedbackControllerPath = CONTROLLER_PATH . '/FeedbackController.php';
        if (!file_exists($feedbackControllerPath)) {
            throw new Exception("FeedbackController.php not found");
        }
        require_once __DIR__ . '/../app/controller/FeedbackController.php';

    }
} catch (Exception $e) {
    logError($e->getMessage());
    die("Error: Missing required controller files. " . $e->getMessage());
}

// Map routes to controllers/views
$routeMap = [
    'dashboard' => 'admin/pages/dashboard.php',
    'products/admin' => 'admin/products/index.php',
    'products/admin/create' => 'admin/products/create.php',
    'products/admin/edit' => 'admin/products/edit.php',
    'blogs/admin' => 'admin/pages/articles/list.php',
    'blogs/admin/create' => 'admin/pages/articles/create.php',
    'blogs/admin/edit' => 'admin/pages/articles/edit.php',
    'feedback/admin' => 'admin/feedback/index.php',
    'feedback/admin/show' => 'admin/feedback/show.php',
    'marketing' => 'admin/pages/marketing.php',
    'users/admin' => 'admin/pages/login.php',
    'settings' => 'admin/pages/settings.php',
    // Add aliases for common routes
    'avis' => 'admin/feedback/index.php',
    'forums' => 'admin/pages/forums.php',
    'evenements' => 'admin/pages/evenements.php',
    'produit' => 'admin/pages/produit.php',
];

// Check auth first
$authController = new AuthController();
if (!$authController->isLoggedIn() || !$authController->isAdmin()) {
    // Not logged in as admin, redirect to login
    header("Location: login.php");
    exit;
}

// Initialize FeedbackController for feedback-related routes
$FeedbackController = null;
try {
    if (strpos($route, 'feedbacks') === 0 || $route === 'avis' || $action) {
        $FeedbackController = new FeedbackController();
    }
} catch (Exception $e) {
    // Log the error
    logError("Error initializing FeedbackController: " . $e->getMessage());
    
    // Set error message in session
    $_SESSION['error_message'] = "System error: Unable to initialize feedback system. Please try again later.";
    
    // Redirect to dashboard
    header("Location: dashboard.php");
    exit;
}

// Handle feedback response submission
if (strpos($route, 'feedback/admin/response') === 0 && isset($_GET['id']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Input validation
        $feedbackId = filter_var($_GET['id'], FILTER_VALIDATE_INT);
        if (!$feedbackId) {
            throw new Exception("Invalid feedback ID");
        }
        
        // Validate response data
        if (empty($_POST['responseMessage']) || strlen(trim($_POST['responseMessage'])) < 3) {
            throw new Exception("Response message is required and must be at least 3 characters");
        }
        
        // Process the response using the controller
        $result = $FeedbackController->addResponse($feedbackId, $_POST);
        
        if (!$result) {
            // Set error message in session
            $FeedbackController->setErrorMessage("Failed to add response.");
        } else {
            // Set success message in session
            $FeedbackController->setSuccessMessage("Response added successfully.");
        }
    } catch (Exception $e) {
        // Log the error
        error_log("Error adding feedback response: " . $e->getMessage());
        
        // Set error message
        $FeedbackController->setErrorMessage("Error: " . $e->getMessage());
    }
    
    // Redirect back to feedback details
    header("Location: dashboard.php?route=feedback/admin/show&id={$feedbackId}");
    exit;
}

// Handle feedback status update
if (strpos($route, 'feedback/admin/status') === 0 && isset($_GET['id']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Input validation
        $feedbackId = filter_var($_GET['id'], FILTER_VALIDATE_INT);
        if (!$feedbackId) {
            throw new Exception("Invalid feedback ID");
        }
        
        // Validate status data
        $validStatuses = ['pending', 'approved', 'rejected'];
        if (isset($_POST['status']) && !in_array($_POST['status'], $validStatuses)) {
            throw new Exception("Invalid status value");
        }
        
        // Process the status update using the controller
        $result = $FeedbackController->updateStatus($feedbackId, $_POST);
        
        if (!$result) {
            // Set error message in session
            $FeedbackController->setErrorMessage("Failed to update feedback status.");
        } else {
            // Set success message in session
            $FeedbackController->setSuccessMessage("Feedback status updated successfully.");
        }
    } catch (Exception $e) {
        // Log the error
        error_log("Error updating feedback status: " . $e->getMessage());
        
        // Set error message
        $FeedbackController->setErrorMessage("Error: " . $e->getMessage());
    }
    
    // Redirect back to feedback list or details
    $redirectUrl = isset($_SERVER['HTTP_REFERER']) && 
                   parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST) === $_SERVER['HTTP_HOST'] ? 
                   $_SERVER['HTTP_REFERER'] : "dashboard.php?route=feedback/admin";
    header("Location: {$redirectUrl}");
    exit;
}

// Handle feedback delete
if (strpos($route, 'feedback/admin/delete') === 0 && isset($_GET['id'])) {
    try {
        // Input validation
        $feedbackId = filter_var($_GET['id'], FILTER_VALIDATE_INT);
        if (!$feedbackId) {
            throw new Exception("Invalid feedback ID");
        }
        
        // CSRF protection - require a token for deletion
        if (!isset($_GET['token']) || $_GET['token'] !== $_SESSION['csrf_token']) {
            // If no CSRF token set yet, create one for future use
            if (!isset($_SESSION['csrf_token'])) {
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            }
            throw new Exception("Security validation failed");
        }
        
        // Delete the feedback
        $result = $FeedbackController->delete($feedbackId);
        
        if (!$result) {
            $FeedbackController->setErrorMessage("Failed to delete feedback.");
        } else {
            $FeedbackController->setSuccessMessage("Feedback deleted successfully.");
        }
    } catch (Exception $e) {
        // Log the error
        error_log("Error deleting feedback: " . $e->getMessage());
        
        // Set error message
        $FeedbackController->setErrorMessage("Error: " . $e->getMessage());
    }
    
    // Redirect back to feedback list
    header("Location: dashboard.php?route=feedback/admin");
    exit;
}

// Handle feedback bulk actions
if ($route === 'feedback/admin/bulk' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/../app/controller/FeedbackController.php';
    $FeedbackController = new FeedbackController();
    try {
        if (!isset($_POST['ids']) || !isset($_POST['action'])) {
            throw new Exception("Missing required parameters");
        }
        
        // Validate IDs
        $ids = [];
        if (is_array($_POST['ids'])) {
            foreach ($_POST['ids'] as $id) {
                $validId = filter_var($id, FILTER_VALIDATE_INT);
                if ($validId) {
                    $ids[] = $validId;
                }
            }
        }
        
        if (empty($ids)) {
            throw new Exception("No valid feedback IDs provided");
        }
        
        // Validate action
        $validActions = ['approve', 'reject', 'delete', 'public', 'private', 'feature', 'unfeature'];
        $bulkAction = $_POST['action'];
        
        if (!in_array($bulkAction, $validActions)) {
            throw new Exception("Invalid bulk action specified");
        }
        
        // Execute the bulk action
        $result = $FeedbackController->bulkAction($ids, $bulkAction);
        
        if ($result) {
            $FeedbackController->setSuccessMessage("Bulk action completed successfully.");
        } else {
            $FeedbackController->setErrorMessage("Failed to complete bulk action.");
        }
    } catch (Exception $e) {
        // Log the error
        error_log("Error in bulk action: " . $e->getMessage());
        
        // Set error message
        $FeedbackController->setErrorMessage("Error: " . $e->getMessage());
    }
    
    // Redirect back to feedback list
    header("Location: dashboard.php?route=feedback/admin");
    exit;
}

// Handle direct controller actions
if ($action && $FeedbackController) {
    switch ($action) {
        case 'create':
            $FeedbackController->create();
            exit;
            
        case 'store':
            $FeedbackController->store();
            exit;
            
        case 'edit':
            $FeedbackController->edit($id);
            exit;
            
        case 'update':
            $FeedbackController->update($id);
            exit;
            
        case 'delete':
            $FeedbackController->delete($id);
            exit;
            
        case 'show':
            $FeedbackController->show($id);
            exit;
            
        case 'index':
            $FeedbackController->index();
            exit;
    }
}

// Prepare admin dashboard content if no specific route
if ($route === 'dashboard') {
    // Check all possible view paths for dashboard
    $dashboardViewPaths = [
        VIEW_PATH . '/' . $routeMap[$route],
        VIEW_PATH . '/back/' . $routeMap[$route],
        VIEW_PATH . '/back/2A27/view/' . $routeMap[$route],
        BASE_PATH . '/app/view/back/2A27/view/' . $routeMap[$route],
        BASE_PATH . '/app/view/back/' . $routeMap[$route]
    ];
    
    $dashboardViewExists = false;
    foreach ($dashboardViewPaths as $path) {
        if (file_exists($path)) {
            $dashboardViewExists = true;
            break;
        }
    }
    
    // Simple dashboard content if no view file exists
    if (!isset($routeMap[$route]) || !$dashboardViewExists) {
        echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GreenMind Admin Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-dark bg-success">
        <a class="navbar-brand" href="#">GreenMind Admin</a>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link text-white" href="login.php?logout=1">Logout</a>
            </li>
        </ul>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h4>Welcome to Admin Dashboard</h4>
                    </div>
                    <div class="card-body">
                        <h5>Currently logged in as: ' . htmlspecialchars($_SESSION['username']) . '</h5>
                        <p>Role: ' . htmlspecialchars($_SESSION['role']) . '</p>
                        
                        <div class="list-group mt-4">
                            <a href="dashboard.php?route=feedback/admin" class="list-group-item list-group-item-action">
                                Manage Feedback
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>';
        exit;
    }
}

// Load appropriate view based on route mapping
try {
    logError("Processing route: {$route}", "DEBUG");
    
    if (isset($routeMap[$route])) {
        // Try different view paths
        $possibleViewPaths = [
            VIEW_PATH . '/' . $routeMap[$route],                          // Standard path
            VIEW_PATH . '/back/' . $routeMap[$route],                     // Back path
            VIEW_PATH . '/back/2A27/view/' . $routeMap[$route],           // Legacy path
            BASE_PATH . '/app/view/back/2A27/view/' . $routeMap[$route],  // Full path
            BASE_PATH . '/app/view/back/' . $routeMap[$route]             // Alternative back path
        ];
        
        $viewFound = false;
        foreach ($possibleViewPaths as $viewFilePath) {
            logError("Checking view path: {$viewFilePath}", "DEBUG");
            if (file_exists($viewFilePath)) {
                logError("Found view at: {$viewFilePath}", "INFO");
                include $viewFilePath;
                $viewFound = true;
                break;
            }
        }
        
        if (!$viewFound) {
            logError("No view found for route: {$route}", "WARNING");
            // Create simple 404 page if proper one doesn't exist
            echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="alert alert-danger">
            <h4>404 - Page Not Found</h4>
            <p>The requested page does not exist.</p>
            <a href="dashboard.php" class="btn btn-primary">Return to Dashboard</a>
        </div>
    </div>
</body>
</html>';
        }
    } else {
        // Check if it's a direct call to a FeedbackController route
        if (strpos($route, 'feedbacks/') === 0 && $feedbackController) {
            // Route is feedback-related but not in the map, pass to controller
            $feedbackController->route();
        } else {
            // Load 404 page
            echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="alert alert-danger">
            <h4>404 - Page Not Found</h4>
            <p>The requested page does not exist.</p>
            <a href="dashboard.php" class="btn btn-primary">Return to Dashboard</a>
        </div>
    </div>
</body>
</html>';
        }
    }
} catch (Exception $e) {
    logError("Error loading view: " . $e->getMessage());
    echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Error</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="alert alert-danger">
            <h4>System Error</h4>
            <p>An error occurred while processing your request.</p>';
            if (ini_get('display_errors')) {
                echo '<p><strong>Error:</strong> ' . htmlspecialchars($e->getMessage()) . '</p>';
            }
    echo '<a href="dashboard.php" class="btn btn-primary">Return to Dashboard</a>
        </div>
    </div>
</body>
</html>';
}
