<?php
require_once '../../../vendor/autoload.php';
require_once '../../../src/models/Marketing.php';
require_once '../../../src/controll/routes/admin/MarketingController.php';

use Dompdf\Dompdf;
use Dompdf\Options;

try {
    if (!isset($_POST['campaign_id'])) {
        throw new Exception('Campaign ID is required');
    }

    $campaignId = filter_var($_POST['campaign_id'], FILTER_VALIDATE_INT);
    if (!$campaignId) {
        throw new Exception('Invalid campaign ID');
    }

    // Get campaign data
    $marketingController = new MarketingController();
    $campaign = $marketingController->getCampaignById($campaignId);
    
    if (!$campaign) {
        throw new Exception('Campaign not found');
    }

    // Configure DOMPDF
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isPhpEnabled', true);

    $dompdf = new Dompdf($options);

    // Generate PDF content
    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; padding: 20px; }
            h1 { color: #3498db; margin-bottom: 20px; }
            .campaign-details { margin-bottom: 30px; }
            .detail-row { margin-bottom: 15px; }
            .label { font-weight: bold; color: #2c3e50; }
            .value { color: #34495e; }
            .description-box {
                background: #f8f9fa;
                padding: 15px;
                border-radius: 5px;
                margin-top: 20px;
            }
            .campaign-header {
                background: #3498db;
                color: white;
                padding: 20px;
                border-radius: 5px;
                margin-bottom: 30px;
            }
            .status-badge {
                display: inline-block;
                padding: 5px 10px;
                border-radius: 15px;
                font-size: 14px;
                margin-top: 10px;
            }
            .status-active { background: #2ecc71; color: white; }
            .status-ended { background: #e74c3c; color: white; }
            .status-upcoming { background: #f1c40f; color: white; }
        </style>
    </head>
    <body>
        <div class="campaign-header">
            <h1>Campaign Report</h1>
            <p>Generated on: ' . date('Y-m-d H:i:s') . '</p>
        </div>

        <div class="campaign-details">
            <div class="detail-row">
                <span class="label">Campaign Name:</span>
                <span class="value">' . htmlspecialchars($campaign['nom_compagne']) . '</span>
            </div>
            
            <div class="detail-row">
                <span class="label">Campaign ID:</span>
                <span class="value">#' . htmlspecialchars($campaign['id']) . '</span>
            </div>

            <div class="detail-row">
                <span class="label">Start Date:</span>
                <span class="value">' . date('F d, Y', strtotime($campaign['date_debut'])) . '</span>
            </div>

            <div class="detail-row">
                <span class="label">End Date:</span>
                <span class="value">' . date('F d, Y', strtotime($campaign['date_fin'])) . '</span>
            </div>

            <div class="detail-row">
                <span class="label">Budget:</span>
                <span class="value">$' . number_format($campaign['budget'], 2) . '</span>
            </div>

            <div class="detail-row">
                <span class="label">Status:</span>';
    
    // Calculate campaign status
    $today = new DateTime();
    $start = new DateTime($campaign['date_debut']);
    $end = new DateTime($campaign['date_fin']);
    $status = ($today < $start) ? 'upcoming' : ($today > $end ? 'ended' : 'active');
    
    $html .= '<span class="status-badge status-' . $status . '">' . ucfirst($status) . '</span>
            </div>

            <div class="description-box">
                <div class="label">Campaign Description:</div>
                <p>' . nl2br(htmlspecialchars($campaign['description'])) . '</p>
            </div>
        </div>
    </body>
    </html>';

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    // Set headers for file download
    $filename = 'campaign_' . preg_replace('/[^A-Za-z0-9]/', '_', $campaign['nom_compagne']) . '_' . date('Y-m-d') . '.pdf';
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: private, max-age=0, must-revalidate');
    header('Pragma: public');

    echo $dompdf->output();
    exit;

} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode(['error' => $e->getMessage()]);
    exit;
}