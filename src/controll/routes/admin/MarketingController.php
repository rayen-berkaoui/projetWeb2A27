<?php
require_once __DIR__ . '/../../../../config/database.php';
require_once __DIR__ . '/../../../models/Marketing.php';
require_once __DIR__ . '/../../../../src/models/Partner.php';

class MarketingController {
    private $model;
    private $partnerModel;

    public function __construct() {
        $this->model = new Marketing();
        $this->partnerModel = new Partner();
    }

    public function index() {
        $campaigns = $this->model->getAllCampaigns();
        $lastId = $this->model->getLastId();
        
        $data = [
            'page_title' => 'Marketing Campaigns',
            'active_menu' => 'marketing',
            'campaigns' => $campaigns,
            'lastId' => $lastId
        ];

        $this->renderView('admin/pages/marketing', $data);
    }

    protected function renderView($viewPath, $data = []) {
        // Get the absolute path to project root
        $projectRoot = realpath(__DIR__ . '/../../../../');
        
        // Set paths for views
        $viewsDir = $projectRoot . '/view/';
        
        // Extract variables
        extract($data);
        
        // Start output buffering
        ob_start();
        
        // Include the view file
        require $viewsDir . $viewPath . '.php';
        $content = ob_get_clean();
        
        // Include the layout
        require $viewsDir . 'admin/layout.php';
    }

    public function add() {
        header('Content-Type: application/json');
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $result = $this->model->addCampaign($_POST);
                if ($result) {
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false, 'error' => 'Failed to add campaign']);
                }
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        exit;
    }

    public function getPartners($campaignId) {
        return $this->partnerModel->getAllPartnersByCampaign($campaignId);
    }

    public function addPartner() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->partnerModel->addPartner($_POST);
            if ($result) {
                header("Location: /2A27/view/admin/pages/marketing.php");
                exit;
            } else {
                echo "Error adding partner";
            }
        }
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

    public function updatePartner($data = null) {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = isset($data) ? $data : $_POST;
                $result = $this->partnerModel->updatePartner($data);
                if ($result) {
                    return true;
                }
            }
            return false;
        } catch (Exception $e) {
            error_log("Error updating partner: " . $e->getMessage());
            return false;
        }
    }
    

    public function delete($id) {
        try {
            return $this->model->deleteCampaign($id);
        } catch (Exception $e) {
            error_log("Controller error in delete: " . $e->getMessage());
            throw $e;
        }
    }

    public function update() {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $result = $this->model->updateCampaign($_POST);
                return $result;
            }
            return false;
        } catch (Exception $e) {
            error_log("Controller error updating campaign: " . $e->getMessage());
            return false;
        }
    }

    public function getCampaign($id) {
        $campaign = $this->model->getCampaignById($id);
        return $campaign;
    }

    public function addCampaign($data) {
        try {
            if (empty($data['nom_compagne']) || empty($data['date_debut']) || 
                empty($data['date_fin']) || empty($data['budget'])) {
                return false;
            }
            
            $campaignData = array(
                'date_debut' => $data['date_debut'],
                'date_fin' => $data['date_fin'],
                'nom_compagne' => $data['nom_compagne'],
                'budget' => $data['budget'],
                'description' => empty($data['description']) ? '' : $data['description']
            );

            return $this->model->addCampaign($campaignData);
        } catch (Exception $e) {
            error_log("Error in addCampaign: " . $e->getMessage());
            return false;
        }
    }

    public function getCampaignById($id) {
        return $this->model->getCampaignById($id);
    }
}
