<?php
require_once '../../../src/controll/routes/admin/MarketingController.php';

header('Content-Type: application/json');

try {
    if (!isset($_GET['campaign_id'])) {
        throw new Exception('Campaign ID is required');
    }

    $controller = new MarketingController();
    $partners = $controller->getPartners($_GET['campaign_id']);
    
    // Debug log
    error_log("Partners returned: " . print_r($partners, true));
    
    echo json_encode($partners);
} catch (Exception $e) {
    error_log("Error in get_partners.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
