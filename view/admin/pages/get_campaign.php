<?php
require_once '../../../src/controll/routes/admin/MarketingController.php';

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    if (!isset($_GET['id'])) {
        throw new Exception('Campaign ID is required');
    }

    $controller = new MarketingController();
    $campaign = $controller->getCampaignById($_GET['id']);

    if (!$campaign) {
        throw new Exception('Campaign not found');
    }

    echo json_encode($campaign);
} catch (Exception $e) {
    error_log("Error in get_campaign.php: " . $e->getMessage());
    http_response_code(404);
    echo json_encode([
        'error' => $e->getMessage()
    ]);
}
