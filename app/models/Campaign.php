<?php

class Campaign {
    private $db;

    public function __construct() {
        // Initialize your database connection here
        $this->db = new PDO("mysql:host=localhost;dbname=your_db", "username", "password");
    }

    public function getAllCampaigns() {
        $stmt = $this->db->query("SELECT * FROM campaigns ORDER BY date_debut DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $sql = "INSERT INTO campaigns (nom_compagne, date_debut, date_fin, budget, description) 
                VALUES (:nom_compagne, :date_debut, :date_fin, :budget, :description)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }
}
