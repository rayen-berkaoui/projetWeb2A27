<?php
require_once __DIR__ . '/../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MarketingController {
    private $campaignModel;
    private $partnerModel;

    public function __construct() {
        $this->campaignModel = new Campaign();
        $this->partnerModel = new Partner();
    }

    public function index() {
        $campaigns = $this->campaignModel->getAllCampaigns();
        require_once 'views/admin/marketing/index.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $campaignData = [
                'nom_compagne' => $_POST['nom_compagne'],
                'date_debut' => $_POST['date_debut'],
                'date_fin' => $_POST['date_fin'],
                'budget' => $_POST['budget'],
                'description' => $_POST['description']
            ];
            
            $this->campaignModel->create($campaignData);
            header('Location: /marketing');
        }
    }

    private function sendConfirmationEmail($partnerEmail, $partnerName, $campaignName) {
        $mail = new PHPMailer(true);
        $config = require __DIR__ . '/../config/mail.php';
        
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = $config['smtp']['host'];
            $mail->SMTPAuth = true;
            $mail->Username = $config['smtp']['username'];
            $mail->Password = $config['smtp']['password'];
            $mail->SMTPSecure = $config['smtp']['encryption'];
            $mail->Port = $config['smtp']['port'];

            // Recipients
            $mail->setFrom('dragona.berkaoui@gmail.com', 'Marketing Team');
            $mail->addAddress($partnerEmail, $partnerName);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Welcome to Our Marketing Campaign!';
            $mail->Body = "
                <h2>Hello {$partnerName}!</h2>
                <p>Thank you for becoming a partner in our {$campaignName} campaign.</p>
                <p>We're excited to have you on board and look forward to a successful partnership.</p>
                <br>
                <p>Best regards,<br>Marketing Team</p>
            ";

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Email sending failed: {$mail->ErrorInfo}");
            return false;
        }
    }

    public function addPartner() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $partnerData = [
                'campaign_id' => $_POST['campaign_id'],
                'nom_entreprise' => $_POST['nom_entreprise'],
                'email' => $_POST['email'],
                'telephone' => $_POST['telephone'],
                'adresse' => $_POST['adresse'],
                'description' => $_POST['description'],
                'statut' => $_POST['statut']
            ];
            
            $partnerId = $this->partnerModel->create($partnerData);
            
            if ($partnerId) {
                // Get campaign name
                $campaign = $this->campaignModel->getCampaignById($partnerData['campaign_id']);
                
                // Send confirmation email
                $this->sendConfirmationEmail(
                    $partnerData['email'],
                    $partnerData['nom_entreprise'],
                    $campaign['nom_compagne']
                );
            }
            
            header('Location: /marketing');
        }
    }
}
