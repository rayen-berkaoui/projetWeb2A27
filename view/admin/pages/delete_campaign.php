<?php
require_once '../../../src/controll/routes/admin/MarketingController.php';

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    if (!isset($_GET['id'])) {
        throw new Exception('Campaign ID is required');
    }

    $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
    if ($id === false) {
        throw new Exception('Invalid campaign ID');
    }

    $controller = new MarketingController();
    $result = $controller->delete($id);

    echo json_encode(array(
        'success' => true,
        'message' => 'Campaign deleted successfully'
    ));
} catch (Exception $e) {
    error_log("Error in delete_campaign.php: " . $e->getMessage());
    echo json_encode(array(
        'success' => false,
        'error' => $e->getMessage()
    ));
}
