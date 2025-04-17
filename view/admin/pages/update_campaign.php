<?php
require_once '../../../src/controll/routes/admin/MarketingController.php';

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    if (!isset($_POST['id'])) {
        throw new Exception('Campaign ID is required');
    }

    $controller = new MarketingController();
    $result = $controller->update();

    echo json_encode(array(
        'success' => $result,
        'message' => $result ? 'Campaign updated successfully' : 'Failed to update campaign'
    ));
} catch (Exception $e) {
    error_log("Error in update_campaign.php: " . $e->getMessage());
    echo json_encode(array(
        'success' => false,
        'error' => $e->getMessage()
    ));
}
