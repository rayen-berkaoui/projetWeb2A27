<?php

require_once __DIR__ . '/admin/ArticleController.php';
require __DIR__ . '/../process/db.php';


// Instantiate controllers
$articleController = new ArticleController($db);
// Base path & URI
$basePath = '/2A27';
$requestUri = $_SERVER['REQUEST_URI'];

// Helper function
function base_url($path = '') {
    return '/2A27/' . ltrim($path, '/');
}

$cleanPath = str_replace($basePath, '', $requestUri);
if ($cleanPath === '') {
    $cleanPath = '/';
}

// Fix: remove query string from cleanPath
$cleanPath = parse_url($cleanPath, PHP_URL_PATH);


// Routing
switch ($cleanPath) {

    // === ROOT REDIRECT ===
    case '/':
        header("Location: {$basePath}/home");
        exit;

    // === FRONTEND USER ROUTES ===
    case '/home':
        require_once __DIR__ . '/../../../view/user/pages/index.php';
        break;

    case '/articles':
        require_once __DIR__ . '/../../../view/user/pages/articles/articles.php';
        break;

    case '/forums':
        require_once __DIR__ . '/../../../view/user/pages/forums/forums.php';
        break;

    case '/evenements':
        require_once __DIR__ . '/../../../view/user/pages/evenements/evenements.php';
        break;

    case '/avis':
        require_once __DIR__ . '/../../../view/user/pages/avis/avis.php';
        break;

    case '/marketing':
        require_once __DIR__ . '/../../../view/user/pages/marketing/marketing.php';
        break;

    case '/login':
        require_once __DIR__ . '/../../../view/user/pages/login/login.php';
        break;
        

    // === ADMIN ROUTES ===
    case '/admin':
    case '/admin/dashboard':
        require_once __DIR__ . '/admin/DashboardController.php';
        (new DashboardController())->index();
        break;

    case '/admin/users':
        echo "Users dashboard - to be implemented";
        break;

    case '/admin/settings':
        echo "Settings dashboard - to be implemented";
        break;

    case '/admin/articles':
        $articleController->index();
        break;
    case '/admin/articles/statistics':
        $stats = $articleController->stats();
        include __DIR__ . '/../../../view/admin/pages/articles/articlestat.php';
        break;
    case '/admin/articles/listTypes':
         // Ensure you have a method in your controller that handles this
        $articleController->listTypes(); 
        break;
    case '/admin/articles/createType':
        $articleController->createType();
        break;

    case (preg_match('/^\/admin\/articles\/editType\/(\d+)$/', $cleanPath, $matches) ? true : false):
        $articleController->editType($matches[1]); // Pass the ID to the editType method
        break;
    
    case (preg_match('/^\/admin\/articles\/deleteType\/(\d+)$/', $cleanPath, $matches) ? true : false):
        $articleController->deleteType($matches[1]); // Pass the ID to the deleteType method
        break;    

    case (preg_match('/^\/admin\/articles\/updateType\/(\d+)$/', $cleanPath, $matches) ? true : false):
        $articleController->updateType($matches[1]);
        break;
            

    case '/admin/export_articles_pdf':
        require_once __DIR__ . '../process/export_articles_pdf.php';
        break;

    case '/admin/articles/create':
    case '/admin/articles/store':
        $articleController->create();
        break;

    case (preg_match('/^\/admin\/articles\/edit\/(\d+)$/', $cleanPath, $matches) ? true : false):
        $articleController->edit($matches[1]);
        break;

    case (preg_match('/^\/admin\/articles\/update\/(\d+)$/', $cleanPath, $matches) ? true : false):
        $articleController->update($matches[1]);
        break;

    case (preg_match('/^\/admin\/articles\/delete\/(\d+)$/', $cleanPath, $matches) ? true : false):
        $articleController->delete($matches[1]);
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

    case '/admin/avis':
        require_once __DIR__ . '/admin/AvisController.php';
        (new AvisController())->index();
        break;

    case '/admin/produit':
        require_once __DIR__ . '/admin/ProduitController.php';
        (new ProduitController())->index();
        break;

    case '/admin/evenements':
        require_once __DIR__ . '/admin/EvenementsController.php';
        (new EvenementsController())->index();
        break;

    // === 404 CASE ===
    default:
        http_response_code(404);
        echo "Page not found: " . htmlspecialchars($cleanPath);
        break;
}
