<?php
require_once __DIR__ . '/admin/ArticleController.php';
require __DIR__ . '/../process/db.php';
require_once __DIR__ . '/admin/PostController.php';
require_once __DIR__ . '/admin/ProduitController.php';
// Instantiate controllers
$articleController = new ArticleController($db);
$postController = new PostController($db);
$produitController = new ProduitController($db);
// Base path & URI
$basePath = '/lezm';
$requestUri = $_SERVER['REQUEST_URI'];

// Helper function
function base_url($path = '') {
    return '/lezm/' . ltrim($path, '/');
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
        
    case '/forum':
       require_once   'C:\xampp\htdocs\lezm\app\views\client\addPost.php';
        break;

    // === POST ROUTES ===
    case '/admin/posts':
    case '/admin/posts/list':
        $postController->index(); // List all posts
        break;

    case '/admin/posts/create':
        $postController->create(); // Create a new post
        break;

    case (preg_match('/^\/admin\/posts\/edit\/(\d+)$/', $cleanPath, $matches) ? true : false):
        $postController->edit($matches[1]); // Edit a post
        break;

    case (preg_match('/^\/admin\/posts\/update\/(\d+)$/', $cleanPath, $matches) ? true : false):
        $postController->update($matches[1]); // Update a post
        break;

    case (preg_match('/^\/admin\/posts\/delete\/(\d+)$/', $cleanPath, $matches) ? true : false):
        $postController->delete($matches[1]); // Delete a post
        break;

    case '/events':
        require_once 'C:\xampp\htdocs\lezm\view\user\pages\events\events.php';
        break;
         case '/produits':
        require_once 'C:\xampp\htdocs\lezm\view\user\pages\produits\produits.php';
        break;
         

    case '/avis':
        require_once 'C:\xampp\htdocs\lezm\view\user\pages\avis\avis.php';
        break;

    case '/marketing':
        require_once __DIR__ . '/../../../view/user/pages/marketing/marketing.php';
        break;

    case '/login':
        require_once __DIR__ . '/../../../pro/view/Front/l2.php';
        break;

    case '/forgotpassword.php':
        require_once __DIR__ . '/../../../pro/view/Front/forgotpassword.php';
        break;

    case '/profile':
        require_once $_SERVER['DOCUMENT_ROOT'] . '/lezm/pro/view/Front/profile.php';
        break;

    // === ADMIN ROUTES ===
    case '/admin':
    case '/admin/dashboard':
        require_once __DIR__ . '/admin/DashboardController.php';
        (new DashboardController())->index();
        break;

    case '/admin/user':
        require_once 'C:\xampp\htdocs\lezm\pro\view\Back\utilisateurs.php';
        break;

    case '/admin/user/formulair':
        require_once 'C:\xampp\htdocs\lezm\pro\view\Back\formulaireaj.php';
        break;

    case (preg_match('/^\/admin\/user\/modifier\.php$/', $cleanPath, $matches) ? true : false):
        if (isset($_GET['id_user'])) {
            $id_user = $_GET['id_user'];
            require_once 'C:\xampp\htdocs\lezm\pro\view\Back\modifier.php';
        }
        break;

    case '/admin/user/export_pdf':
        require_once 'C:\xampp\htdocs\lezm\pro\view\Back\export_pdf.php';
        break;

    case '/admin/user/ajout_succes':
        require_once 'C:\xampp\htdocs\lezm\pro\view\Back\ajout_succes.php';
        break;

    case '/admin/articles':
        $articleController->index();
        break;

    case '/admin/articles/statistics':
        $stats = $articleController->stats();
        include __DIR__ . '/../../../view/admin/pages/articles/articlestat.php';
        break;

    case '/admin/articles/listTypes':
        $articleController->listTypes();
        break;

    case '/admin/articles/createType':
        $articleController->createType();
        break;

    case (preg_match('/^\/admin\/articles\/editType\/(\d+)$/', $cleanPath, $matches) ? true : false):
        $articleController->editType($matches[1]);
        break;

    case (preg_match('/^\/admin\/articles\/deleteType\/(\d+)$/', $cleanPath, $matches) ? true : false):
        $articleController->deleteType($matches[1]);
        break;

    case (preg_match('/^\/admin\/articles\/updateType\/(\d+)$/', $cleanPath, $matches) ? true : false):
        $articleController->updateType($matches[1]);
        break;

    case '/admin/export_articles_pdf':
        require_once __DIR__ . '/../process/export_articles_pdf.php';
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
        require_once __DIR__ . '/admin/PostController.php';
        (new PostController())->index();
        break;

    // === AVIS ROUTES ===
    case '/admin/avis':
        require_once __DIR__ . '/admin/AvisController.php';
        $avisController = new AvisController($db);
        
        break;

    case '/admin/avis/create':
        require_once __DIR__ . '/admin/AvisController.php';
        $avisController = new AvisController($db);
        $avisController->createAvis();
        break;

    case (preg_match('/^\/admin\/avis\/edit\/(\d+)$/', $cleanPath, $matches) ? true : false):
        require_once __DIR__ . '/admin/AvisController.php';
        $avisController = new AvisController($db);
        $avisController->editAvis($matches[1]);
        break;

    case (preg_match('/^\/admin\/avis\/update\/(\d+)$/', $cleanPath, $matches) ? true : false):
        require_once __DIR__ . '/admin/AvisController.php';
        $avisController = new AvisController($db);
        $avisController->updateAvis($matches[1]);
        break;

    case (preg_match('/^\/admin\/avis\/delete\/(\d+)$/', $cleanPath, $matches) ? true : false):
        require_once __DIR__ . '/admin/AvisController.php';
        $avisController = new AvisController($db);
        $avisController->deleteAvis($matches[1]);
        break;

    // === COMMENTAIRES ROUTES ===
    case '/admin/commentaires':
        require_once __DIR__ . '/admin/AvisController.php';
        $avisController = new AvisController($db);
        $avisController->indexCommentaires();
        break;

    case '/admin/commentaires/create':
        require_once __DIR__ . '/admin/AvisController.php';
        $avisController = new AvisController($db);
        $avisController->createCommentaire();
        break;

    case (preg_match('/^\/admin\/commentaires\/edit\/(\d+)$/', $cleanPath, $matches) ? true : false):
        require_once __DIR__ . '/admin/AvisController.php';
        $avisController = new AvisController($db);
        $avisController->editCommentaire($matches[1]);
        break;

    case (preg_match('/^\/admin\/commentaires\/update\/(\d+)$/', $cleanPath, $matches) ? true : false):
        require_once __DIR__ . '/admin/AvisController.php';
        $avisController = new AvisController($db);
        $avisController->updateCommentaire($matches[1]);
        break;

    case (preg_match('/^\/admin\/commentaires\/delete\/(\d+)$/', $cleanPath, $matches) ? true : false):
        require_once __DIR__ . '/admin/AvisController.php';
        $avisController = new AvisController($db);
        $avisController->deleteCommentaire($matches[1]);
        break;

    case '/admin/produit':
        require_once __DIR__ . '/admin/ProduitController.php';
        (new ProduitController())->index();
        break;
        case '/admin/categorie':
        require_once 'C:\xampp\htdocs\lezm\view\user\pages\produits\categories.php';
        break;
    case '/admin/produit/create':
        require_once __DIR__ . '/../../../view/user/pages/produits/AjoutProduitForm.php';
        break;
    case '/admin/modifierProduit':
        require_once __DIR__ . '/../../../view/user/pages/produits/modifierProduitForm.php';

        break;

    case '/admin/supprimerProduit':
        require_once __DIR__ . '/../../../view/user/pages/produits/supprimerProduit.php';

        break;

            case '/admin/produit/ajoutProduit':
        require_once __DIR__ . '/../../../view/user/pages/produits/ajoutProduit.php';
        break;

    case '/admin/evenements':
        require_once __DIR__ . '/admin/EvenementController.php';
        $evenementController = new EvenementController($db);
        $evenementController->indexEvenements();
        break;

    case '/admin/evenements/create':
        require_once __DIR__ . '/admin/EvenementController.php';
        $evenementController = new EvenementController($db);
        $evenementController->createEvenement();
        break;

    case (preg_match('/^\/admin\/evenements\/edit\/(\d+)$/', $cleanPath, $matches) ? true : false):
        require_once __DIR__ . '/admin/EvenementController.php';
        $evenementController = new EvenementController($db);
        $evenementController->editEvenement($matches[1]);
        break;

    case (preg_match('/^\/admin\/evenements\/update\/(\d+)$/', $cleanPath, $matches) ? true : false):
        require_once __DIR__ . '/admin/EvenementController.php';
        $evenementController = new EvenementController($db);
        $evenementController->updateEvenement($matches[1]);
        break;

    case (preg_match('/^\/admin\/evenements\/delete\/(\d+)$/', $cleanPath, $matches) ? true : false):
        require_once __DIR__ . '/admin/EvenementController.php';
        $evenementController = new EvenementController($db);
        $evenementController->deleteEvenement($matches[1]);
        break;

    // === RESERVATIONS ROUTES ===
    case '/admin/reservations':
        require_once __DIR__ . '/admin/EvenementController.php';
        $evenementController = new EvenementController($db);
        $evenementController->indexReservations();
        break;

    case '/admin/reservations/create':
        require_once __DIR__ . '/admin/EvenementController.php';
        $evenementController = new EvenementController($db);
        $evenementController->createReservation();
        break;

    case (preg_match('/^\/admin\/reservations\/edit\/(\d+)$/', $cleanPath, $matches) ? true : false):
        require_once __DIR__ . '/admin/EvenementController.php';
        $evenementController = new EvenementController($db);
        $evenementController->editReservation($matches[1]);
        break;

    case (preg_match('/^\/admin\/reservations\/update\/(\d+)$/', $cleanPath, $matches) ? true : false):
        require_once __DIR__ . '/admin/EvenementController.php';
        $evenementController = new EvenementController($db);
        $evenementController->updateReservation($matches[1]);
        break;

    case (preg_match('/^\/admin\/reservations\/delete\/(\d+)$/', $cleanPath, $matches) ? true : false):
        require_once __DIR__ . '/admin/EvenementController.php';
        $evenementController = new EvenementController($db);
        $evenementController->deleteReservation($matches[1]);
        break;

    case (preg_match('/^\/events\/reserve\/(\d+)$/', $cleanPath, $matches) ? true : false):
        require_once __DIR__ . '/admin/EvenementController.php';
        $evenementController = new EvenementController($db);
        $evenementController->publicReserveForm($matches[1]);
        break;

    case '/events/reserve/submit':
        require_once __DIR__ . '/admin/EvenementController.php';
        $evenementController = new EvenementController($db);
        $evenementController->submitPublicReservation();
        break;

    // === 404 CASE ===
    default:
        http_response_code(404);
        echo "Page not found: " . htmlspecialchars($cleanPath);
        break;
}