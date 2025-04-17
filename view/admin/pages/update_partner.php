<?php
require_once '../../../src/controll/routes/admin/MarketingController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    error_log("Received update request: " . print_r($_POST, true));
    
    if (empty($_POST['id']) || empty($_POST['campaign_id'])) {
        echo "error";
        exit;
    }

    $controller = new MarketingController();
    $result = $controller->updatePartner($_POST);
    echo $result ? "success" : "error";
} else {
    echo "error";
}
