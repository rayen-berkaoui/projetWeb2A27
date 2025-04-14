<?php
require_once __DIR__ . '/admin/ArticleController.php';
$basePath = '/2A27';
$requestUri = $_SERVER['REQUEST_URI'];

// Database connection (replace with your actual database credentials)
$db = new mysqli('localhost', 'root', '', 'db_html');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Now instantiate the controller and pass the $db connection
$articleController = new ArticleController($db);

// Call the required method, for example:
$articleController->index(); // Default method if needed

// Add this at the top of router.php
function base_url($path = '') {
    return '/2A27/' . ltrim($path, '/');
}

// Remove base path if present
$cleanPath = str_replace($basePath, '', $requestUri);

// Handle the root URL case
if ($cleanPath === '') {
    $cleanPath = '/';
}

switch ($cleanPath) {
    case '/':
        // Redirect to dashboard if accessing root
        header("Location: {$basePath}/admin/dashboard");
        exit;

    case '/admin':
        // Redirect /admin to /admin/dashboard
        header("Location: {$basePath}/admin/dashboard");
        exit;

    case '/admin/dashboard':
        require_once __DIR__ . '/admin/DashboardController.php';
        (new DashboardController())->index();
        break;

    case '/admin/users':
        // Future users controller
        echo "Users dashboard - to be implemented";
        break;

    case '/admin/settings':
        // Future settings controller
        echo "Settings dashboard - to be implemented";
        break;

    case '/admin/articles':
        require_once __DIR__ . '/admin/ArticleController.php';
        $articleController->index(); // Show all articles
        break;

    case '/admin/articles/create':
        require_once __DIR__ . '/admin/ArticleController.php';
        $articleController->create(); // Create new article
        break;

    case '/admin/articles/edit':
        require_once __DIR__ . '/admin/ArticleController.php';
        $articleController->edit(); // Edit article
        break;

    case '/admin/articles/delete':
        require_once __DIR__ . '/admin/ArticleController.php';
        $articleController->delete(); // Delete article
        break;

    case '/admin/login':
        require_once __DIR__ . '/admin/LoginController.php';
        (new LoginController())->index();
        break;

    case '/admin/marketing':
        require_once __DIR__ . '/admin/MarketingController.php';
        (new MarketingController())->index();
        break;

    case '/admin/forums':
        require_once __DIR__ . '/admin/ForumsController.php';
        (new ForumsController())->index();
        break;

    case '/admin/feedback':
        require_once __DIR__ . '/admin/FeedbackController.php';
        (new FeedbackController($db))->index();
        break;

    case '/admin/produit':
        require_once __DIR__ . '/admin/ProduitController.php';
        (new ProduitController())->index();
        break;

    case '/admin/evenements':
        require_once __DIR__ . '/admin/EvenementsController.php';
        (new EvenementsController())->index();
        break;

    default:
        http_response_code(404);
        echo "Page not found: " . htmlspecialchars($cleanPath);
        break;
}

// Define routes array (for potential future route-based logic)
$routes = [
    '/admin/products' => 'ProductController@index',
    '/admin/products/add' => 'ProductController@create',
    '/admin/products/edit/{id}' => 'ProductController@edit',
    '/admin/articles' => 'ArticleController@index',
    '/admin/articles/create' => 'ArticleController@create',
    '/admin/articles/edit/{id}' => 'ArticleController@edit',
    '/admin/articles/delete/{id}' => 'ArticleController@delete',
    '/admin/feedback' => 'FeedbackController@index',
    '/admin/marketing' => 'MarketingController@index',
    '/admin/experiments' => 'ExperimentController@index',
    '/admin/partners' => 'PartnerController@index',
    '/admin/reservations' => 'ReservationController@index',
];
