<?php
class EvenementController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // =========================
    // Evenement Methods
    // =========================

    public function indexEvenements() {
        $stmt = $this->db->query("SELECT * FROM evenement ORDER BY date DESC");
        $evenements = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        return $evenements; // Return the fetched events
    }
    

    public function createEvenement() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titre = $_POST['titre'];
            $description = $_POST['description'];
            $date = $_POST['date'];
            $lieu = $_POST['lieu'];

            $query = "INSERT INTO evenement (titre, description, date, lieu) VALUES (?, ?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$titre, $description, $date, $lieu]);

            header("Location: /2A27/admin/evenements");
            exit;
        }

        include 'C:\xampp\htdocs\2A27\view\admin\pages\evenements\create.php';
    }

    public function editEvenement($id) {
        $stmt = $this->db->prepare("SELECT * FROM evenement WHERE id = ?");
        $stmt->execute([$id]);
        $evenement = $stmt->fetch(PDO::FETCH_ASSOC);

        include 'C:\xampp\htdocs\2A27\view\admin\pages\evenements\edit.php';
    }

    public function updateEvenement($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titre = $_POST['titre'];
            $description = $_POST['description'];
            $date = $_POST['date'];
            $lieu = $_POST['lieu'];

            $query = "UPDATE evenement SET titre = ?, description = ?, date = ?, lieu = ? WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$titre, $description, $date, $lieu, $id]);

            header("Location: /2A27/admin/evenements");
            exit;
        }
    }

    public function deleteEvenement($id) {
        $stmt = $this->db->prepare("DELETE FROM evenement WHERE id = ?");
        $stmt->execute([$id]);

        header("Location: /2A27/admin/evenements");
        exit;
    }

    // =========================
    // Reservation Methods
    // =========================

    public function indexReservations() {
        $query = "SELECT reservations.*, evenement.titre AS evenement_nom 
                  FROM reservations 
                  JOIN evenement ON reservations.id_evenement = evenement.id";  // Corrected to 'id_evenement'
        $stmt = $this->db->query($query);
        $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        include 'C:\xampp\htdocs\2A27\view\admin\pages\reservations\list.php';
    }

    public function createReservation() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom_participant = $_POST['nom_participant'];
            $email = $_POST['email'];
            $date_reservation = $_POST['date_reservation'];
            $evenement_id = $_POST['evenement_id'];  // Corrected to 'id_evenement'
    
            // Use the correct column name 'id_evenement' for insertion
            $query = "INSERT INTO reservations (nom_participant, email, date_reservation, id_evenement) VALUES (?, ?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$nom_participant, $email, $date_reservation, $evenement_id]);
    
            header("Location: /2A27/admin/reservations");
            exit;
        }
    
        // Get all events to populate select dropdown
        $stmt = $this->db->query("SELECT id, titre FROM evenement");
        $evenements = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        include 'C:\xampp\htdocs\2A27\view\admin\pages\reservations\create.php';
    }

    public function editReservation($id) {
        // Fetch reservation details, including the evenement_id
        $stmt = $this->db->prepare("SELECT * FROM reservations WHERE id_reservation = ?");
        $stmt->execute([$id]);
        $reservation = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // Get all events to populate select dropdown
        $evenements = $this->db->query("SELECT id, titre FROM evenement")->fetchAll(PDO::FETCH_ASSOC);
    
        // Pass the reservation data and events to the view
        include 'C:\xampp\htdocs\2A27\view\admin\pages\reservations\edit.php';
    }
    

    public function updateReservation($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom_participant = $_POST['nom_participant'];
            $email = $_POST['email'];
            $date_reservation = $_POST['date_reservation'];
            $evenement_id = $_POST['evenement_id'];  // Corrected to 'id_evenement'

            $query = "UPDATE reservations SET nom_participant = ?, email = ?, date_reservation = ?, id_evenement = ? WHERE id_reservation = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$nom_participant, $email, $date_reservation, $evenement_id, $id]);

            header("Location: /2A27/admin/reservations");
            exit;
        }
    }

    public function deleteReservation($id) {
        $stmt = $this->db->prepare("DELETE FROM reservations WHERE id_reservation = ?");
        $stmt->execute([$id]);

        header("Location: /2A27/admin/reservations");
        exit;
    }
    public function publicReserveForm($eventId) {
        // Fetch the event details
        $stmt = $this->db->prepare("SELECT * FROM evenement WHERE id = ?");
        $stmt->execute([$eventId]);
        $evenement = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (!$evenement) {
            echo "Event not found";
            exit;
        }
    
        include 'C:\xampp\htdocs\2A27\view\user\events\reserve.php'; // You should create this view
    }
    public function submitPublicReservation() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = $_POST['nom'];
            $email = $_POST['email'];
            $date = date('Y-m-d');
            $eventId = $_POST['event_id'];
    
            $stmt = $this->db->prepare("INSERT INTO reservations (nom_participant, email, date_reservation, id_evenement) VALUES (?, ?, ?, ?)");
            $stmt->execute([$nom, $email, $date, $eventId]);
    
            header("Location: /2A27/events"); // Or back to a confirmation page
            exit;
        }
    }
    
}
?>
