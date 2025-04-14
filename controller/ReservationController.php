<?php
require_once __DIR__ . '/../model/Reservation.php';
require_once __DIR__ . '/../model/Evenement.php';

class ReservationController {
    private $reservationModel;
    private $evenementModel;

    public function __construct() {
        $this->reservationModel = new Reservation();
        $this->evenementModel = new Evenement();
    }

    // Créer une nouvelle réservation
    public function create($data) {
        try {
            // Validation des données
            if (empty($data['nom']) || empty($data['email']) || empty($data['id_evenement'])) {
                throw new Exception("Tous les champs sont obligatoires");
            }

            // Vérifier si l'événement existe
            $evenement = $this->evenementModel->getById($data['id_evenement']);
            if (!$evenement) {
                throw new Exception("L'événement n'existe pas");
            }

            // Vérifier si l'événement n'est pas passé
            if (strtotime($evenement['date_evenement']) < strtotime('today')) {
                throw new Exception("Cet événement est déjà passé");
            }

            // Vérifier si l'utilisateur n'a pas déjà réservé
            if ($this->reservationModel->checkExistingReservation($data['email'], $data['id_evenement'])) {
                throw new Exception("Vous avez déjà réservé pour cet événement");
            }

            // Créer la réservation
            $id_reservation = $this->reservationModel->create($data);
            
            return [
                'success' => true,
                'message' => 'Réservation effectuée avec succès',
                'id_reservation' => $id_reservation
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    // Supprimer une réservation
    public function delete($id_reservation) {
        try {
            if ($this->reservationModel->delete($id_reservation)) {
                return [
                    'success' => true,
                    'message' => 'Réservation supprimée avec succès'
                ];
            }
            throw new Exception("Erreur lors de la suppression");
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    // Récupérer toutes les réservations avec les détails des événements
    public function getAllReservations() {
        try {
            return [
                'success' => true,
                'data' => $this->reservationModel->getAllWithEvents()
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    // Récupérer les réservations d'un événement spécifique
    public function getReservationsByEvent($id_evenement) {
        try {
            // Vérifier si l'événement existe
            $evenement = $this->evenementModel->getById($id_evenement);
            if (!$evenement) {
                throw new Exception("L'événement n'existe pas");
            }

            return [
                'success' => true,
                'data' => $this->reservationModel->getByEventId($id_evenement),
                'evenement' => $evenement
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    // Obtenir les statistiques des réservations
    public function getStats() {
        try {
            return [
                'success' => true,
                'data' => $this->reservationModel->getStats()
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    // Vérifier la disponibilité d'un événement
    public function checkAvailability($id_evenement, $limite = 3) {
        try {
            $count = $this->reservationModel->countByEventId($id_evenement);
            return [
                'success' => true,
                'available' => $count < $limite,
                'count' => $count,
                'remaining' => $limite - $count
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
} 