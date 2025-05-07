<?php
require_once __DIR__ . '/../config.php';

class Evenement {
    private $connexion;
    private $table = "evenement";

    // Propriétés
    public $id;
    public $titre;
    public $description;
    public $date;
    public $lieu;
    public $created_at;

    public function __construct() {
        $this->connexion = config::getConnexion();
    }

    public function add($titre, $description, $date, $lieu) {
        $stmt = $this->connexion->prepare("INSERT INTO " . $this->table . " (titre, description, date, lieu) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$titre, $description, $date, $lieu]);
    }

    // Récupérer tous les événements
    public function getAll() {
        try {
            $query = "SELECT * FROM " . $this->table . " ORDER BY date ASC";
            $stmt = $this->connexion->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            throw new Exception("Erreur lors de la récupération des événements : " . $e->getMessage());
        }
    }

    // Récupérer un événement par son ID
    public function getById($id) {
        try {
            $query = "SELECT * FROM " . $this->table . " WHERE id = ?";
            $stmt = $this->connexion->prepare($query);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            throw new Exception("Erreur lors de la récupération de l'événement : " . $e->getMessage());
        }
    }

    // Créer un événement
    public function create($data) {
        try {
            $query = "INSERT INTO " . $this->table . " 
                    (titre, description, date, lieu) 
                    VALUES (:titre, :description, :date, :lieu)";
            
            $stmt = $this->connexion->prepare($query);
            $stmt->execute([
                ':titre' => $data['titre'],
                ':description' => $data['description'],
                ':date' => $data['date'],
                ':lieu' => $data['lieu']
            ]);
            return $this->connexion->lastInsertId();
        } catch(PDOException $e) {
            throw new Exception("Erreur lors de la création de l'événement : " . $e->getMessage());
        }
    }

    // Mettre à jour un événement
    public function update($id, $data) {
        try {
            $query = "UPDATE " . $this->table . " 
                    SET titre = :titre, 
                        description = :description, 
                        date = :date, 
                        lieu = :lieu 
                    WHERE id = :id";
            
            $stmt = $this->connexion->prepare($query);
            return $stmt->execute([
                ':titre' => $data['titre'],
                ':description' => $data['description'],
                ':date' => $data['date'],
                ':lieu' => $data['lieu'],
                ':id' => $id
            ]);
        } catch(PDOException $e) {
            throw new Exception("Erreur lors de la mise à jour de l'événement : " . $e->getMessage());
        }
    }

    // Supprimer un événement
    public function delete($id) {
        try {
            $query = "DELETE FROM " . $this->table . " WHERE id = ?";
            $stmt = $this->connexion->prepare($query);
            return $stmt->execute([$id]);
        } catch(PDOException $e) {
            throw new Exception("Erreur lors de la suppression de l'événement : " . $e->getMessage());
        }
    }

    // Récupérer les événements à venir
    public function getUpcoming() {
        try {
            $query = "SELECT * FROM " . $this->table . " 
                    WHERE date >= CURDATE() 
                    ORDER BY date ASC 
                    LIMIT 5";
            $stmt = $this->connexion->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            throw new Exception("Erreur lors de la récupération des événements à venir : " . $e->getMessage());
        }
    }

    // Récupérer les événements populaires
    public function getPopular() {
        try {
            $query = "SELECT e.*, COUNT(r.id_reservation) as nb_reservations 
                    FROM " . $this->table . " e 
                    LEFT JOIN reservations r ON e.id = r.id_evenement 
                    GROUP BY e.id 
                    ORDER BY nb_reservations DESC, e.date ASC 
                    LIMIT 5";
            $stmt = $this->connexion->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            throw new Exception("Erreur lors de la récupération des événements populaires : " . $e->getMessage());
        }
    }
    
    // Récupérer les statistiques des événements
    public function getStats() {
        try {
            $stats = [
                'total' => 0,
                'a_venir' => 0,
                'ce_mois' => 0,
                'lieux' => []
            ];

            // Total des événements
            $query = "SELECT COUNT(*) as total FROM " . $this->table;
            $stmt = $this->connexion->query($query);
            $stats['total'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

            // Événements à venir
            $query = "SELECT COUNT(*) as a_venir FROM " . $this->table . " WHERE date >= CURDATE()";
            $stmt = $this->connexion->query($query);
            $stats['a_venir'] = $stmt->fetch(PDO::FETCH_ASSOC)['a_venir'];

            // Événements ce mois
            $query = "SELECT COUNT(*) as ce_mois FROM " . $this->table . " 
                    WHERE MONTH(date) = MONTH(CURDATE()) 
                    AND YEAR(date) = YEAR(CURDATE())";
            $stmt = $this->connexion->query($query);
            $stats['ce_mois'] = $stmt->fetch(PDO::FETCH_ASSOC)['ce_mois'];

            // Lieux uniques
            $query = "SELECT DISTINCT lieu FROM " . $this->table;
            $stmt = $this->connexion->query($query);
            $stats['lieux'] = $stmt->fetchAll(PDO::FETCH_COLUMN);

            return $stats;
        } catch(PDOException $e) {
            throw new Exception("Erreur lors de la récupération des statistiques : " . $e->getMessage());
        }
    }
}