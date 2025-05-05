<?php
require_once __DIR__ . '/../config.php';

class Reservation {
    private $connexion;
    private $table = "reservations";

    // Propriétés
    public $id_reservation;
    public $nom_participant;
    public $email;
    public $date_reservation;
    public $id_evenement;

    public function __construct() {
        $this->connexion = config::getConnexion();
    }

    // Créer une réservation
    public function create($data) {
        try {
            $query = "INSERT INTO " . $this->table . " 
                    (nom_participant, email, id_evenement) 
                    VALUES (:nom, :email, :id_evenement)";
            
            $stmt = $this->connexion->prepare($query);
            $stmt->execute([
                ':nom' => $data['nom'],
                ':email' => $data['email'],
                ':id_evenement' => $data['id_evenement']
            ]);
            return $this->connexion->lastInsertId();
        } catch(PDOException $e) {
            throw new Exception("Erreur lors de la création de la réservation : " . $e->getMessage());
        }
    }

    // Récupérer toutes les réservations avec les détails des événements
    public function getAllWithEvents() {
        try {
            $query = "SELECT r.*, e.titre as titre_evenement, e.date_evenement, e.lieu 
                    FROM " . $this->table . " r
                    INNER JOIN evenements e ON r.id_evenement = e.id_evenement
                    ORDER BY r.date_reservation DESC";
            $stmt = $this->connexion->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            throw new Exception("Erreur lors de la récupération des réservations : " . $e->getMessage());
        }
    }

    // Récupérer les réservations d'un événement
    public function getByEventId($id_evenement) {
        try {
            $query = "SELECT * FROM " . $this->table . " WHERE id_evenement = ?";
            $stmt = $this->connexion->prepare($query);
            $stmt->execute([$id_evenement]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            throw new Exception("Erreur lors de la récupération des réservations : " . $e->getMessage());
        }
    }

    // Supprimer une réservation
    public function delete($id) {
        try {
            $query = "DELETE FROM " . $this->table . " WHERE id_reservation = ?";
            $stmt = $this->connexion->prepare($query);
            return $stmt->execute([$id]);
        } catch(PDOException $e) {
            throw new Exception("Erreur lors de la suppression de la réservation : " . $e->getMessage());
        }
    }

    // Vérifier si un email a déjà réservé pour un événement
    public function checkExistingReservation($email, $id_evenement) {
        try {
            $query = "SELECT COUNT(*) as count FROM " . $this->table . " 
                    WHERE email = ? AND id_evenement = ?";
            $stmt = $this->connexion->prepare($query);
            $stmt->execute([$email, $id_evenement]);
            return $stmt->fetch(PDO::FETCH_ASSOC)['count'] > 0;
        } catch(PDOException $e) {
            throw new Exception("Erreur lors de la vérification de la réservation : " . $e->getMessage());
        }
    }

    // Compter le nombre de réservations pour un événement
    public function countByEventId($id_evenement) {
        try {
            $query = "SELECT COUNT(*) as count FROM " . $this->table . " WHERE id_evenement = ?";
            $stmt = $this->connexion->prepare($query);
            $stmt->execute([$id_evenement]);
            return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        } catch(PDOException $e) {
            throw new Exception("Erreur lors du comptage des réservations : " . $e->getMessage());
        }
    }

    // Récupérer les statistiques des réservations
    public function getStats() {
        try {
            $stats = [
                'total' => 0,
                'aujourd_hui' => 0,
                'cette_semaine' => 0
            ];

            // Total des réservations
            $query = "SELECT COUNT(*) as total FROM " . $this->table;
            $stmt = $this->connexion->query($query);
            $stats['total'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

            // Réservations aujourd'hui
            $query = "SELECT COUNT(*) as aujourd_hui FROM " . $this->table . " 
                    WHERE DATE(date_reservation) = CURDATE()";
            $stmt = $this->connexion->query($query);
            $stats['aujourd_hui'] = $stmt->fetch(PDO::FETCH_ASSOC)['aujourd_hui'];

            // Réservations cette semaine
            $query = "SELECT COUNT(*) as cette_semaine FROM " . $this->table . " 
                    WHERE date_reservation >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
            $stmt = $this->connexion->query($query);
            $stats['cette_semaine'] = $stmt->fetch(PDO::FETCH_ASSOC)['cette_semaine'];

            return $stats;
        } catch(PDOException $e) {
            throw new Exception("Erreur lors de la récupération des statistiques : " . $e->getMessage());
        }
    }
} 