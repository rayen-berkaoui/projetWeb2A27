<?php
require_once '../../../src/controllers/admin/MarketingController.php';

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    // Validate required fields
    $required = ['nom_compagne', 'date_debut', 'date_fin', 'budget'];
    $missing = array_filter($required, function($field) {
        return !isset($_POST[$field]) || trim($_POST[$field]) === '';
    });

    if (!empty($missing)) {
        throw new Exception('Missing required fields: ' . implode(', ', $missing));
    }

    // Create campaign data array
    $campaignData = [
        'nom_compagne' => trim($_POST['nom_compagne']),
        'date_debut' => $_POST['date_debut'],
        'date_fin' => $_POST['date_fin'],
        'budget' => floatval($_POST['budget']),
        'description' => isset($_POST['description']) ? trim($_POST['description']) : ''
    ];

    $controller = new MarketingController();
    $newId = $controller->addCampaign($campaignData);

    if ($newId) {
        $campaignData['id'] = $newId;
        echo json_encode([
            'success' => true,
            'message' => 'Campaign added successfully',
            'data' => $campaignData
        ]);
    } else {
        throw new Exception('Failed to add campaign');
    }
} catch (Exception $e) {
    error_log("Error in add_campaign.php: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'debug' => [
            'post_data' => $_POST,
            'trace' => $e->getTraceAsString()
        ]
    ]);
}