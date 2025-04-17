<?php
require_once '../../../src/controll/routes/admin/MarketingController.php';

if (isset($_GET['id'])) {
    $controller = new MarketingController();
    $partner = $controller->getPartnerById($_GET['id']);
    echo json_encode($partner);
} else {
    echo "error";
}
