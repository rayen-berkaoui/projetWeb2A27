<?php
class FeedbackModel {
    private $db;
    private $table = 'feedbacks';

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    /**
     * Get approved feedbacks for public display with pagination
     */
    public function getPublicFeedback(int $perPage = 5, int $offset = 0): array {
        try {
            $stmt = $this->db->prepare("
                SELECT id, name, email, message as commentaire, rating as note, created_at, featured 
                FROM {$this->table} 
                WHERE status = 'approved' AND is_public = 1
                ORDER BY created_at DESC 
                LIMIT :limit OFFSET :offset
            ");
            $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching public feedback: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Count total approved feedbacks
     */
    public function getTotalPublicCount(): int {
        try {
            $stmt = $this->db->prepare("
                SELECT COUNT(*) as total FROM {$this->table} 
                WHERE status = 'approved' AND is_public = 1
            ");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int) $result['total'];
        } catch (PDOException $e) {
            error_log("Error counting public feedback: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Calculate average rating of approved feedbacks
     */
    public function getAverageRating(): float {
        try {
            $stmt = $this->db->prepare("
                SELECT AVG(rating) as average FROM {$this->table} 
                WHERE status = 'approved' AND is_public = 1
            ");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return round($result['average'] ?? 0, 1);
        } catch (PDOException $e) {
            error_log("Error calculating average rating: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get featured feedbacks
     */
    public function getFeaturedFeedback(int $limit = 6): array {
        try {
            $stmt = $this->db->prepare("
                SELECT id, name, email, message as commentaire, 
                       rating as note, created_at 
                FROM {$this->table} 
                WHERE status = 'approved' AND featured = 1
                ORDER BY created_at DESC 
                LIMIT :limit
            ");
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching featured feedback: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Save new feedback submission
     */
    public function saveFeedback(array $data): bool {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO {$this->table} 
                (name, email, message, rating, status, is_public) 
                VALUES (:name, :email, :message, :rating, 'pending', 0)
            ");
            
            return $stmt->execute([
                ':name' => $data['name'],
                ':email' => $data['email'] ?? null,
                ':message' => $data['message'],
                ':rating' => $data['rating']
            ]);
        } catch (PDOException $e) {
            error_log("Error saving feedback: " . $e->getMessage());
            return false;
        }
    }
}

    
    public function getFeedbacks() {
        $query = "SELECT * FROM feedbacks"; // Adapte cette requête à ton schéma de base de données
        $stmt = $this->conn->query($query);
        $feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $feedbacks;
    }
    public function getAllFeedbacks($page = 1, $perPage = 10, $status = null) {
        try {
            // Calculate offset for pagination
            $offset = ($page - 1) * $perPage;
            
            // Base query
            $query = "SELECT * FROM {$this->table}";
            $params = [];
            
            // Add status filter if provided
            if ($status) {
                $query .= " WHERE status = :status";
                $params[':status'] = $status;
            }
            
            // Add sorting and pagination
            $query .= " ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
            $params[':limit'] = $perPage;
            $params[':offset'] = $offset;
            
            // Prepare and execute query
            $stmt = $this->db->prepare($query);
            
            // Bind parameters
            foreach ($params as $param => $value) {
                $type = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
                $stmt->bindValue($param, $value, $type);
            }
            
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Error fetching feedbacks: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get approved feedbacks for public display
     * 
     * @param int $page Page number
     * @param int $perPage Items per page
     * @return array Array of approved feedbacks
     */
    public function getApprovedFeedbacks($page = 1, $perPage = 5) {
        return $this->getAllFeedbacks($page, $perPage, 'approved');
    }
    
    
    
    /**
     * Count total feedbacks
     * 
     * @param string $status Filter by status (optional)
     * @return int Total count
     */
    public function countFeedbacks($status = null) {
        try {
            $query = "SELECT COUNT(*) as total FROM {$this->table}";
            $params = [];
            
            if ($status) {
                $query .= " WHERE status = :status";
                $params[':status'] = $status;
            }
            
            $stmt = $this->db->prepare($query);
            
            // Bind parameters if needed
            foreach ($params as $param => $value) {
                $stmt->bindValue($param, $value, PDO::PARAM_STR);
            }
            
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return (int) $result['total'] ?? 0;
        } catch (PDOException $e) {
            error_log("Error counting feedbacks: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Count approved feedbacks
     * 
     * @return int Total approved feedbacks
     */
    public function countApprovedFeedbacks() {
        return $this->countFeedbacks('approved');
    }
    
    /**
     * Get feedback by ID
     * 
     * @param int $id Feedback ID
     * @return array|null Feedback data or null if not found
     */
    public function getFeedbackById($id) {
        try {
            $query = "SELECT * FROM {$this->table} WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $e) {
            error_log("Error fetching feedback by ID: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Create new feedback
     * 
     * @param array $data Feedback data
     * @return bool|int Feedback ID on success, false on failure
     */
    public function createFeedback($data) {
        try {
            $query = "INSERT INTO {$this->table} (nom, prenom, commentaire, note, status) 
                      VALUES (:nom, :prenom, :commentaire, :note, :status)";
                      
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':nom', $data['nom'], PDO::PARAM_STR);
            $stmt->bindParam(':prenom', $data['prenom'], PDO::PARAM_STR);
            $stmt->bindParam(':commentaire', $data['commentaire'], PDO::PARAM_STR);
            $stmt->bindParam(':note', $data['note'], PDO::PARAM_INT);
            
            // Default status is 'pending'
            $status = 'pending';
            $stmt->bindParam(':status', $status, PDO::PARAM_STR);
            
            if ($stmt->execute()) {
                return $this->db->lastInsertId();
            } else {
                return false;
            }
        } catch (PDOException $e) {
            error_log("Error creating feedback: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update feedback status
     * 
     * @param int $id Feedback ID
     * @param string $status New status (approved, rejected, pending)
     * @return bool Success
     */
    public function updateStatus($id, $status) {
        try {
            $query = "UPDATE {$this->table} SET status = :status WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':status', $status, PDO::PARAM_STR);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error updating feedback status: " . $e->getMessage());
            return false;
        }
    }
    
    
    public function updateVisibility($id, $isPublic) {
        try {
            $query = "UPDATE {$this->table} SET is_public = :is_public WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':is_public', $isPublic, PDO::PARAM_BOOL);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error updating feedback visibility: " . $e->getMessage());
            return false;
        }
    }
    
   
    public function updateFeedback($id, $data) {
        try {
            $query = "UPDATE {$this->table} 
                      SET nom = :nom, prenom = :prenom, commentaire = :commentaire, note = :note 
                      WHERE id = :id";
            
            $stmt = $this->db->prepare($query);
    
            // Lier les paramètres
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':nom', $data['nom'], PDO::PARAM_STR);
            $stmt->bindParam(':prenom', $data['prenom'], PDO::PARAM_STR);
            $stmt->bindParam(':commentaire', $data['commentaire'], PDO::PARAM_STR);
            $stmt->bindParam(':note', $data['note'], PDO::PARAM_INT);
    
            // Exécuter la requête
            if ($stmt->execute()) {
                return true;  // Retourner true si l'exécution réussie
            } else {
                return false; // Retourner false si une erreur se produit
            }
    
        } catch (PDOException $e) {
            error_log("Error updating feedback: " . $e->getMessage());
            return false;  // Retourner false en cas d'exception
        }
    }
    
}
