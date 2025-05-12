<?php

require_once __DIR__ . '/admin/ArticleController.php';
require_once __DIR__ . '/../process/db.php';

set_error_handler(function($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

try {
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

        case '/events':
            require_once __DIR__ . '/../../../view/user/events/events.php';
            break;

        case '/avis':
            require_once __DIR__ . '/../../../view/user/pages/avis/avis.php';
            break;

        case '/marketing':
            require_once __DIR__ . '/../../../view/user/pages/marketing/marketing.php';
            break;

        case '/login':
            require_once __DIR__ . '/../../../view/user/pages/auth/login.php';
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
                require_once __DIR__ . '/admin/EvenementController.php';
                $evenementController = new EvenementController($db);
                $evenementController->indexEvenements(); // List all events
                break;
            
            case '/admin/evenements/create':
                require_once __DIR__ . '/admin/EvenementController.php';
                $evenementController = new EvenementController($db);
                $evenementController->createEvenement(); // Create a new event
                break;
            
            case (preg_match('/^\/admin\/evenements\/edit\/(\d+)$/', $cleanPath, $matches) ? true : false):
                require_once __DIR__ . '/admin/EvenementController.php';
                $evenementController = new EvenementController($db);
                $evenementController->editEvenement($matches[1]); // Edit event by ID
                break;
            
            case (preg_match('/^\/admin\/evenements\/update\/(\d+)$/', $cleanPath, $matches) ? true : false):
                require_once __DIR__ . '/admin/EvenementController.php';
                $evenementController = new EvenementController($db);
                $evenementController->updateEvenement($matches[1]); // Update event by ID
                break;
            
            case (preg_match('/^\/admin\/evenements\/delete\/(\d+)$/', $cleanPath, $matches) ? true : false):
                require_once __DIR__ . '/admin/EvenementController.php';
                $evenementController = new EvenementController($db);
                $evenementController->deleteEvenement($matches[1]); // Delete event by ID
                break;
                // === ADMIN ROUTES FOR RESERVATIONS ===
    case '/admin/reservations':
        require_once __DIR__ . '/admin/EvenementController.php';
        $evenementController = new EvenementController($db);
        $evenementController->indexReservations(); // List all reservations
        break;

    case '/admin/reservations/create':
        require_once __DIR__ . '/admin/EvenementController.php';
        $evenementController = new EvenementController($db);
        $evenementController->createReservation(); // Create a new reservation
        break;

    case (preg_match('/^\/admin\/reservations\/edit\/(\d+)$/', $cleanPath, $matches) ? true : false):
        require_once __DIR__ . '/admin/EvenementController.php';
        $evenementController = new EvenementController($db);
        $evenementController->editReservation($matches[1]); // Edit reservation by ID
        break;

    case (preg_match('/^\/admin\/reservations\/update\/(\d+)$/', $cleanPath, $matches) ? true : false):
        require_once __DIR__ . '/admin/EvenementController.php';
        $evenementController = new EvenementController($db);
        $evenementController->updateReservation($matches[1]); // Update reservation by ID
        break;

    case (preg_match('/^\/admin\/reservations\/delete\/(\d+)$/', $cleanPath, $matches) ? true : false):
        require_once __DIR__ . '/admin/EvenementController.php';
        $evenementController = new EvenementController($db);
        $evenementController->deleteReservation($matches[1]); // Delete reservation by ID
        break;

    case (preg_match('/^\/admin\/reservations\/confirm\/(\d+)$/', $cleanPath, $matches) ? true : false):
        require_once __DIR__ . '/admin/EvenementController.php';
        $evenementController = new EvenementController($db);
        $evenementController->confirmerReservation($matches[1]); // Confirm reservation by ID
        break;

    case (preg_match('/^\/events\/reserve\/(\d+)$/', $cleanPath, $matches) ? true : false):
        require_once __DIR__ . '/admin/EvenementController.php'; // adjust if it's in a different folder
        $evenementController = new EvenementController($db);
        $evenementController->publicReserveForm($matches[1]); // Add this method
        break;
    case '/events/reserve/submit':
        require_once __DIR__ . '/admin/EvenementController.php';
        $evenementController = new EvenementController($db);
        $evenementController->submitPublicReservation(); // Add this method
        break;

        case '/test-email':
            require_once __DIR__ . '/../../../controller/EvenementController.php';
            $controller = new EvenementController();
            $controller->testEmail();
            break;

        case '/admin/test-email':
        case '/2A27/admin/test-email':
            require_once __DIR__ . '/admin/EvenementController.php';
            $evenementController = new EvenementController($db);
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $evenementController->testEmail();
            } else {
                $evenementController->testEmailForm();
            }
            break;

        case '/admin/verify-email':
        case '/2A27/admin/verify-email':
            require_once __DIR__ . '/admin/EvenementController.php';
            $evenementController = new EvenementController($db);
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $evenementController->testEmail();
            } else {
                $evenementController->testEmailForm();
            }
            break;

        // === 404 CASE ===
        default:
            http_response_code(404);
            echo "Page not found: " . htmlspecialchars($cleanPath);
            break;
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    http_response_code(500);
    echo "Une erreur s'est produite. Veuillez réessayer plus tard.";
}
