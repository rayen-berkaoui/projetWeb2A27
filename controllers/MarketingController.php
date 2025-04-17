<?php

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
            
            $this->partnerModel->create($partnerData);
            header('Location: /marketing');
        }
    }
}
