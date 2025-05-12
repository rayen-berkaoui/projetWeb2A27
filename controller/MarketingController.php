<?php
require_once __DIR__ . '/../../../../config/database.php';
require_once __DIR__ . '/../../models/Marketing.php';
require_once __DIR__ . '/../../../../src/models/Partner.php';

class MarketingController {
    private $campaignModel;
    private $partnerModel;

    public function __construct() {
        $this->campaignModel = new Marketing();
        $this->partnerModel = new Partner();
    }

    public function index() {
        $campaigns = $this->campaignModel->getAllCampaigns();
        $data = [
            'page_title' => 'Marketing Campaigns',
            'active_menu' => 'marketing',
            'campaigns' => $campaigns
        ];
        $this->renderView('admin/pages/marketing', $data);
    }

    protected function renderView($viewPath, $data = []) {
        $projectRoot = realpath(__DIR__ . '/../../../../');
        $viewsDir = $projectRoot . '/view/';
        extract($data);
        ob_start();
        require $viewsDir . $viewPath . '.php';
        $content = ob_get_clean();
        require $viewsDir . 'admin/layout.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $campaignData = [
                'nom_compagne' => $_POST['nom_compagne'],
                'date_debut' => $_POST['date_debut'],
                'date_fin' => $_POST['date_fin'],
                'budget' => $_POST['budget'],
                'description' => isset($_POST['description']) ? $_POST['description'] : ''
            ];
            if ($this->campaignModel->addCampaign($campaignData)) {
                header('Location: /marketing');
                exit;
            }
            echo json_encode(['success' => false, 'error' => 'Failed to add campaign']);
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $result = $this->campaignModel->updateCampaign($_POST);
                return $result ? true : false;
            } catch (Exception $e) {
                error_log("Controller error updating campaign: " . $e->getMessage());
                return false;
            }
        }
    }

    public function delete($id) {
        try {
            return $this->campaignModel->deleteCampaign($id);
        } catch (Exception $e) {
            error_log("Controller error in delete: " . $e->getMessage());
            throw $e;
        }
    }

    public function getCampaignById($id) {
        return $this->campaignModel->getCampaignById($id);
    }

    public function addPartner() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $partnerData = [
                'campaign_id' => $_POST['campaign_id'],
                'nom_entreprise' => $_POST['nom_entreprise'],
                'email' => $_POST['email'],
                'telephone' => $_POST['telephone'],
                'adresse' => $_POST['adresse'],
                'description' => isset($_POST['description']) ? $_POST['description'] : '',
                'statut' => $_POST['statut']
            ];
            if ($this->partnerModel->addPartner($partnerData)) {
                header('Location: /marketing');
                exit;
            }
            echo "Error adding partner";
        }
    }

    public function getPartners($campaignId) {
        return $this->partnerModel->getAllPartnersByCampaign($campaignId);
    }

    public function getPartnerById($id) {
        try {
            if (!$id) {
                throw new Exception("Invalid partner ID");
            }
            return $this->partnerModel->getPartnerById($id);
        } catch (Exception $e) {
            error_log("Error in getPartnerById: " . $e->getMessage());
            throw $e;
        }
    }

    public function updatePartner() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $result = $this->partnerModel->updatePartner($_POST);
                return $result ? true : false;
            } catch (Exception $e) {
                error_log("Error updating partner: " . $e->getMessage());
                return false;
            }
        }
    }
public function addCampaign($campaignData) {
    return $this->campaignModel->addCampaign($campaignData);
}}
 