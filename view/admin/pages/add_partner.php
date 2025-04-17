<?php
require_once '../../../src/models/Partner.php';
require_once '../../../config/Database.php';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Validate required fields
        if (empty($_POST)) {
            throw new Exception('No data provided');
        }

        $partner = new Partner();
        $result = $partner->addPartner($_POST);

        if ($result) {
            echo "success|Partner added successfully";
        } else {
            throw new Exception('Failed to add partner');
        }
    } else {
        throw new Exception('Invalid request method');
    }
} catch (Exception $e) {
    http_response_code(400);
    echo "error|" . $e->getMessage();
}
