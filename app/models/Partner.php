<?php
class Partner {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->createTableIfNotExists();
    }

    private function createTableIfNotExists() {
        // Create partner table
        $sql = "CREATE TABLE IF NOT EXISTS partenaire (
            id INT PRIMARY KEY,
            nom_entreprise VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            telephone VARCHAR(50) NOT NULL,
            adresse TEXT NOT NULL,
            description TEXT,
            statut VARCHAR(50) DEFAULT 'active'
        )";
        $this->db->exec($sql);
    }

    private function reorderIds() {
        try {
            $this->db->beginTransaction();

            // Get all partners ordered by ID
            $stmt = $this->db->query("SELECT id FROM partenaire ORDER BY id");
            $partners = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Update IDs sequentially
            $newId = 1;
            foreach ($partners as $partner) {
                $updateStmt = $this->db->prepare("UPDATE partenaire SET id = ? WHERE id = ?");
                $updateStmt->execute(array($newId, $partner['id']));
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

    public function getNextId() {
        try {
            $stmt = $this->db->query("SELECT COUNT(*) as count FROM partenaire");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['count'] + 1;
        } catch (PDOException $e) {
            error_log("Error in getNextId: " . $e->getMessage());
            return 1;
        }
    }

    public function addPartner($data) {
        try {
            $this->db->beginTransaction();
            
            $nextId = $this->getNextId();
            // First insert the partner
            $stmt = $this->db->prepare("INSERT INTO partenaire 
                (id, nom_entreprise, email, telephone, adresse, description, statut)
                VALUES (:id, :nom_entreprise, :email, :telephone, :adresse, :description, :statut)");
            
            $result = $stmt->execute(array(
                ':id' => $nextId,
                ':nom_entreprise' => $data['nom_entreprise'],
                ':email' => $data['email'],
                ':telephone' => $data['telephone'],
                ':adresse' => $data['adresse'],
                ':description' => $data['description'],
                ':statut' => $data['statut']
            ));

            if ($result && isset($data['campaign_id'])) {
                // Update the marketing campaign to link this partner
                $marketingStmt = $this->db->prepare("UPDATE marketing SET partenaire_id = :partner_id WHERE ID = :campaign_id");
                $marketingResult = $marketingStmt->execute(array(
                    ':partner_id' => $nextId,
                    ':campaign_id' => $data['campaign_id']
                ));
                
                if (!$marketingResult) {
                    $this->db->rollBack();
                    return false;
                }
            }
            
            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Error in addPartner: " . $e->getMessage());
            return false;
        }
    }

    public function getPartnerById($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM partenaire WHERE id = ?");
            $stmt->execute(array($id));
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getPartnerById: " . $e->getMessage());
            throw $e;
        }
    }

    public function updatePartner($data) {
        try {
            $this->db->beginTransaction();

            // If status is not set, set it to 'active'
            $data['statut'] = isset($data['statut']) ? $data['statut'] : 'active';

            // Update partner details
            $stmt = $this->db->prepare("UPDATE partenaire SET 
                nom_entreprise = :nom_entreprise,
                email = :email,
                telephone = :telephone,
                adresse = :adresse,
                description = :description,
                statut = :statut
                WHERE id = :id");
            
            $result = $stmt->execute(array(
                ':id' => $data['id'],
                ':nom_entreprise' => $data['nom_entreprise'],
                ':email' => $data['email'],
                ':telephone' => $data['telephone'],
                ':adresse' => $data['adresse'],
                ':description' => isset($data['description']) ? $data['description'] : '',
                ':statut' => $data['statut']
            ));

            // Update campaign association if campaign_id is provided
            if ($result && isset($data['campaign_id'])) {
                // First, remove any existing association
                $stmt = $this->db->prepare("UPDATE marketing SET partenaire_id = NULL WHERE partenaire_id = ?");
                $stmt->execute(array($data['id']));
                
                // Then set the new association
                $stmt = $this->db->prepare("UPDATE marketing SET partenaire_id = ? WHERE ID = ?");
                $stmt->execute(array($data['id'], $data['campaign_id']));
            }

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Error in updatePartner: " . $e->getMessage());
            return false;
        }
    }

    public function deletePartner($id) {
        try {
            $this->db->beginTransaction();
            
            // First, update any marketing campaigns to remove this partner
            $stmt = $this->db->prepare("UPDATE marketing SET partenaire_id = NULL WHERE partenaire_id = ?");
            $stmt->execute(array($id));
            
            // Then delete the partner
            $stmt = $this->db->prepare("DELETE FROM partenaire WHERE id = ?");
            $result = $stmt->execute(array($id));
            
            if ($result) {
                $this->db->commit();
                return true;
            }
            
            $this->db->rollBack();
            return false;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Error in deletePartner: " . $e->getMessage());
            return false;
        }
    }

    public function getAllPartnersByCampaign($campaignId) {
        try {
            // Debug log
            error_log("Getting partners for campaign ID: " . $campaignId);
            
            $stmt = $this->db->prepare("
                SELECT p.* 
                FROM partenaire p 
                INNER JOIN marketing m ON m.partenaire_id = p.id 
                WHERE m.ID = ? AND p.id IS NOT NULL
            ");
            $stmt->execute(array($campaignId));
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Set default status if null
            foreach ($result as &$partner) {
                $partner['statut'] = isset($partner['statut']) ? $partner['statut'] : 'active';
            }
            
            // Debug log
            error_log("Query result: " . print_r($result, true));
            return $result;
        } catch (PDOException $e) {
            error_log("Error getting partners: " . $e->getMessage());
            error_log("SQL State: " . $e->errorInfo[0]);
            return array();
        }
    }
}
