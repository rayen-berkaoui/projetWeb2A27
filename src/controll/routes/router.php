<?php
require_once __DIR__ . '/admin/MarketingController.php';
require_once __DIR__ . '/admin/DashboardController.php';

$basePath = '/2A27';
$requestUri = $_SERVER['REQUEST_URI'];

// Remove base path if present
$cleanPath = str_replace($basePath, '', $requestUri);

// Handle the root URL case
if ($cleanPath === '' || $cleanPath === '/') {
    $cleanPath = '/admin/dashboard';
}

switch ($cleanPath) {
    case '/admin/dashboard':
        require_once __DIR__ . '/admin/DashboardController.php';
        (new DashboardController())->index();
        break;

    case '/admin/marketing':
        require_once __DIR__ . '/admin/MarketingController.php';
        (new MarketingController())->index();
        break;

    default:
        http_response_code(404);
        echo "Page not found: " . htmlspecialchars($cleanPath);
        break;
}
