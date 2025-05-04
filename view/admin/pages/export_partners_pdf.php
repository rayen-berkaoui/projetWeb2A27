<?php
require_once '../../../vendor/autoload.php';
require_once '../../../src/models/Partner.php';
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

    // Get campaign and partner data
    $marketingController = new MarketingController();
    $campaign = $marketingController->getCampaignById($campaignId);
    
    $partnerModel = new Partner();
    $partners = $partnerModel->getAllPartnersByCampaign($campaignId);

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
            body { font-family: Arial, sans-serif; }
            h1 { color: #3498db; }
            table { width: 100%; border-collapse: collapse; margin: 20px 0; }
            th, td { padding: 10px; border: 1px solid #ddd; }
            th { background: #3498db; color: white; }
            tr:nth-child(even) { background: #f9f9f9; }
        </style>
    </head>
    <body>
        <h1>Campaign Partners Report</h1>
        <h2>' . htmlspecialchars($campaign['nom_compagne']) . '</h2>
        <p>Generated on: ' . date('Y-m-d H:i:s') . '</p>
        
        <table>
            <thead>
                <tr>
                    <th>Company Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>';

    foreach ($partners as $partner) {
        $html .= sprintf(
            '<tr>
                <td>%s</td>
                <td>%s</td>
                <td>%s</td>
                <td>%s</td>
                <td>%s</td>
            </tr>',
            htmlspecialchars($partner['nom_entreprise']),
            htmlspecialchars($partner['email']),
            htmlspecialchars($partner['telephone']),
            htmlspecialchars($partner['adresse']),
            htmlspecialchars($partner['statut'])
        );
    }

    $html .= '</tbody></table></body></html>';

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    // Set headers for file download
    $filename = 'partners_' . $campaign['nom_compagne'] . '_' . date('Y-m-d') . '.pdf';
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