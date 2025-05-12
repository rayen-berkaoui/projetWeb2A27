<?php

require_once __DIR__ . '/Database.php';

class Marketing {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->createTableIfNotExists();
    }

    public function getAllCampaigns() {
        $stmt = $this->db->query("SELECT ID as id, Ncompagne as nom_compagne, DateD as date_debut, 
                                 DateF as date_fin, Budget as budget, `Desc` as description 
                                 FROM marketing ORDER BY ID ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getNextId() {
        try {
            $stmt = $this->db->query("SELECT COUNT(*) as count FROM marketing");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['count'] + 1;
        } catch (PDOException $e) {
            error_log("Error in getNextId: " . $e->getMessage());
            return 1;
        }
    }

    public function addCampaign($data) {
        try {
            $nextId = $this->getNextId();
            $stmt = $this->db->prepare("INSERT INTO marketing (ID, DateD, DateF, Ncompagne, Budget, `Desc`) 
                VALUES (:id, :date_debut, :date_fin, :nom_compagne, :budget, :description)");
            
            return $stmt->execute(array(
                ':id' => $nextId,
                ':date_debut' => $data['date_debut'],
                ':date_fin' => $data['date_fin'],
                ':nom_compagne' => $data['nom_compagne'],
                ':budget' => $data['budget'],
                ':description' => $data['description']
            ));
        } catch (PDOException $e) {
            error_log("Error in addCampaign: " . $e->getMessage());
            return false;
        }
    }

    private function createTableIfNotExists() {
        $sql = "CREATE TABLE IF NOT EXISTS marketing (
            ID INT PRIMARY KEY,
            DateD DATE NOT NULL,
            DateF DATE NOT NULL,
            Ncompagne VARCHAR(255) NOT NULL,
            Budget DECIMAL(10,2) NOT NULL,
            `Desc` TEXT
        )";
        $this->db->exec($sql);
    }

    public function getLastId() {
        try {
            $stmt = $this->db->query("SELECT COALESCE(MAX(id), 0) as maxId FROM marketing_campaign");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)$result['maxId'];
        } catch (PDOException $e) {
            error_log("Error getting last ID: " . $e->getMessage());
            return 0;
        }
    }

    public function deleteCampaign($id) {
        try {
            // Begin transaction
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            
            $this->db->beginTransaction();
            
            // Delete the campaign
            $deleteCampaignStmt = $this->db->prepare("DELETE FROM marketing WHERE ID = ?");
            $result = $deleteCampaignStmt->execute(array($id));
            
            if ($result) {
                // Get all remaining campaigns ordered by ID
                $stmt = $this->db->query("SELECT ID FROM marketing ORDER BY ID");
                $campaigns = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                // Update IDs sequentially
                $newId = 1;
                foreach ($campaigns as $campaign) {
                    $updateStmt = $this->db->prepare("UPDATE marketing SET ID = ? WHERE ID = ?");
                    $updateStmt->execute(array($newId, $campaign['ID']));
                    $newId++;
                }
                
                $this->db->commit();
                return true;
            }
            
            $this->db->rollBack();
            throw new Exception('Failed to delete campaign');
        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            error_log("Error in deleteCampaign: " . $e->getMessage());
            throw $e;
        }
    }

    public function updateCampaign($data) {
        try {
            $stmt = $this->db->prepare("UPDATE marketing SET 
                DateD = :date_debut,
                DateF = :date_fin,
                Ncompagne = :nom_compagne,
                Budget = :budget,
                `Desc` = :description
                WHERE ID = :id");
            
            $params = array(
                ':id' => $data['id'],
                ':date_debut' => $data['date_debut'],
                ':date_fin' => $data['date_fin'],
                ':nom_compagne' => $data['nom_compagne'],
                ':budget' => floatval($data['budget']),
                ':description' => $data['description']
            );
            
            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("Error in updateCampaign: " . $e->getMessage());
            return false;
        }
    }

    public function getCampaignById($id) {
        try {
            $stmt = $this->db->prepare("SELECT 
                ID as id,
                DateD as date_debut,
                DateF as date_fin,
                Ncompagne as nom_compagne,
                Budget as budget,
                `Desc` as description
                FROM marketing 
                WHERE ID = ?");
            
            $stmt->execute(array($id));
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getCampaignById: " . $e->getMessage());
            return false;
        }
    }

    public function reorderIds() {
        try {
            $this->db->beginTransaction();

            // Get all campaigns ordered by ID
            $stmt = $this->db->query("SELECT ID FROM marketing ORDER BY ID");
            $campaigns = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Update IDs sequentially
            $newId = 1;
            foreach ($campaigns as $campaign) {
                $updateStmt = $this->db->prepare("UPDATE marketing SET ID = ? WHERE ID = ?");
                $updateStmt->execute(array($newId, $campaign['ID']));
                $newId++;
            }

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Error in reorderIds: " . $e->getMessage());
            return false;
        }
    }
}
