<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once __DIR__ . '/../../../../view/vendors/PHPMailer/PHPMailer-master/src/Exception.php';
require_once __DIR__ . '/../../../../view/vendors/PHPMailer/PHPMailer-master/src/PHPMailer.php';
require_once __DIR__ . '/../../../../view/vendors/PHPMailer/PHPMailer-master/src/SMTP.php';

class EvenementController {
    private $db;
    private $smtpEmail = 'amaniltaifi02@gmail.com';
    private $smtpPassword = 'rjlg hzvo iwjt qajd'; // Nouveau mot de passe d'application sans espaces

    public function __construct($db) {
        $this->db = $db;
    }

    // =========================
    // Evenement Methods
    // =========================

    public function indexEvenements() {
        $stmt = $this->db->query("SELECT * FROM evenement ORDER BY date DESC");
        $evenements = $stmt->fetchAll(PDO::FETCH_ASSOC);
        include 'C:\xampp1\htdocs\2A27\view\admin\pages\evenements\list.php';
    }

    public function createEvenement() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titre = $_POST['titre'];
            $description = $_POST['description'];
            $date = $_POST['date_evenement'];
            $lieu = $_POST['lieu'];
            $admin_email = $_POST['admin_email'];

            try {
                $query = "INSERT INTO evenement (titre, description, date, lieu) VALUES (?, ?, ?, ?)";
                $stmt = $this->db->prepare($query);
                
                if ($stmt->execute([$titre, $description, $date, $lieu])) {
                    // Si l'insertion réussit, envoyer l'email
                    $this->sendConfirmationEmail($titre, $date, $admin_email);
                    header("Location: /2A27/admin/evenements");
                    exit;
                }
            } catch (Exception $e) {
                error_log("Erreur lors de la création de l'événement : " . $e->getMessage());
            }
        }

        include 'C:\xampp1\htdocs\2A27\view\admin\pages\evenements\create.php';
    }

    private function sendConfirmationEmail($titre, $date_evenement, $admin_email) {
        $mail = new PHPMailer(true);

        try {
            // Debug détaillé
            $mail->SMTPDebug = 3;
            $mail->Debugoutput = function($str, $level) {
                error_log("PHPMailer Debug: $str");
            };
            
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = $this->smtpEmail;
            $mail->Password = trim($this->smtpPassword);
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;
            
            // Augmenter le timeout
            $mail->Timeout = 60;
            $mail->SMTPKeepAlive = true;
            
            // SSL Options
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            $mail->CharSet = 'UTF-8';

            // Configuration de l'email
            $mail->setFrom($this->smtpEmail, 'GreenMind Events');
            $mail->addAddress($admin_email, 'Admin GreenMind');
            $mail->addReplyTo($this->smtpEmail, 'GreenMind Events');

            // Format HTML
            $mail->isHTML(true);
            $mail->Subject = 'Confirmation - Nouvel événement créé';

            // Formatage de la date
            $date_formattee = date('d/m/Y', strtotime($date_evenement));

            // Corps du message
            $mail->Body = "
                <html>
                <head>
                    <style>
                        body { font-family: Arial, sans-serif; }
                        .container { padding: 20px; max-width: 600px; margin: 0 auto; background-color: #f8f9fa; }
                        .header { background-color: #4CAF50; color: white; padding: 20px; border-radius: 8px 8px 0 0; text-align: center; }
                        .content { margin: 30px 0; line-height: 1.8; background-color: white; padding: 25px; border-radius: 8px; }
                        .footer { color: white; font-size: 14px; text-align: center; padding: 15px; background-color: #4CAF50; border-radius: 0 0 8px 8px; }
                        .logo { font-size: 24px; font-weight: bold; margin-bottom: 10px; }
                        .message { font-size: 18px; margin: 20px 0; color: #333; }
                        .thank-you { font-size: 20px; color: #4CAF50; font-weight: bold; margin-top: 20px; }
                    </style>
                </head>
                <body>
                    <div class='container'>
                        <div class='header'>
                            <div class='logo'>GreenMind Events</div>
                            <h2>Confirmation de Réservation</h2>
                        </div>
                        <div class='content'>
                            <p class='message'>Merci pour votre visite à GreenMind !</p>
                            <p class='message'>Votre réservation a été confirmée avec succès.</p>
                            <p class='thank-you'>À très bientôt !</p>
                        </div>
                        <div class='footer'>
                            <p>© " . date('Y') . " GreenMind - Tous droits réservés</p>
                        </div>
                    </div>
                </body>
                </html>
            ";

            $mail->AltBody = "Merci pour votre visite à GreenMind !\n\nVotre réservation est confirmée.\n\nÀ très bientôt !";

            return $mail->send();
        } catch (Exception $e) {
            error_log("Erreur lors de l'envoi du mail : " . $e->getMessage());
            return false;
        }
    }

    public function editEvenement($id) {
        $stmt = $this->db->prepare("SELECT * FROM evenement WHERE id = ?");
        $stmt->execute([$id]);
        $evenement = $stmt->fetch(PDO::FETCH_ASSOC);

        include 'C:\xampp1\htdocs\2A27\view\admin\pages\evenements\edit.php';
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
        // Supprimer les réservations liées à cet événement
        $stmt = $this->db->prepare("DELETE FROM reservations WHERE id_evenement = ?");
        $stmt->execute([$id]);
    
        // Maintenant, tu peux supprimer l'événement
        $stmt = $this->db->prepare("DELETE FROM evenement WHERE id = ?");
        $stmt->execute([$id]);
    
        header("Location: /2A27/admin/evenements");
        exit;
    }
    
    

    // =========================
    // Reservation Methods
    // =========================

    public function indexReservations() {
        $query = "SELECT r.*, e.titre AS evenement_nom, e.date AS date_evenement
                  FROM reservations r
                  JOIN evenement e ON r.id_evenement = e.id
                  ORDER BY r.date_reservation DESC";
        $stmt = $this->db->query($query);
        $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        include 'C:\xampp1\htdocs\2A27\view\admin\pages\reservations\list.php';
    }

    public function createReservation() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom_participant = $_POST['nom_participant'];
            $email = $_POST['email'];
            $date_reservation = $_POST['date_reservation'];
            $evenement_id = $_POST['evenement_id'];  // Corrected to 'id_evenement'
    
            // Use the correct column name 'id_evenement' for insertion and add default status
            $query = "INSERT INTO reservations (nom_participant, email, date_reservation, id_evenement, statut) VALUES (?, ?, ?, ?, 'en_attente')";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$nom_participant, $email, $date_reservation, $evenement_id]);
    
            header("Location: /2A27/admin/reservations");
            exit;
        }
    
        // Get all events to populate select dropdown
        $stmt = $this->db->query("SELECT id, titre FROM evenement");
        $evenements = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        include 'C:\xampp1\htdocs\2A27\view\admin\pages\reservations\create.php';
    }

    public function editReservation($id) {
        // Fetch reservation details, including the evenement_id
        $stmt = $this->db->prepare("SELECT * FROM reservations WHERE id_reservation = ?");
        $stmt->execute([$id]);
        $reservation = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // Get all events to populate select dropdown
        $evenements = $this->db->query("SELECT id, titre FROM evenement")->fetchAll(PDO::FETCH_ASSOC);
    
        // Pass the reservation data and events to the view
        include 'C:\xampp1\htdocs\2A27\view\admin\pages\reservations\edit.php';
    }
    

    public function updateReservation($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom_participant = $_POST['nom_participant'];
            $email = $_POST['email'];
            $date_reservation = $_POST['date_reservation'];
            $evenement_id = $_POST['evenement_id'];
            $statut = isset($_POST['statut']) ? $_POST['statut'] : 'en_attente';

            $query = "UPDATE reservations SET nom_participant = ?, email = ?, date_reservation = ?, id_evenement = ?, statut = ? WHERE id_reservation = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$nom_participant, $email, $date_reservation, $evenement_id, $statut, $id]);

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
// Vérifier si l'ID de l'événement est valide
$stmt = $this->db->prepare("SELECT * FROM evenement WHERE id = ?");
$stmt->execute([$eventId]);
$evenement = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$evenement) {
    // Si l'événement n'existe pas, afficher un message d'erreur
    echo "L'événement demandé est introuvable.";
    exit;
}

// Passer l'événement à la vue
include 'C:\xampp1\htdocs\2A27\view\user\events\reserve.php';
}
    public function submitPublicReservation() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = $_POST['nom'];
            $email = $_POST['email'];
            $date = date('Y-m-d');
            $eventId = $_POST['event_id'];
    
            try {
                // D'abord, récupérer les détails de l'événement
                $stmt = $this->db->prepare("SELECT * FROM evenement WHERE id = ?");
                $stmt->execute([$eventId]);
                $evenement = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if (!$evenement) {
                    throw new Exception("L'événement n'existe pas");
                }

                // Vérifier si l'utilisateur n'a pas déjà réservé
                $stmt = $this->db->prepare("SELECT COUNT(*) FROM reservations WHERE email = ? AND id_evenement = ?");
                $stmt->execute([$email, $eventId]);
                if ($stmt->fetchColumn() > 0) {
                    throw new Exception("Vous avez déjà réservé pour cet événement");
                }

                // Insérer la réservation avec le statut en attente
                $stmt = $this->db->prepare("INSERT INTO reservations (nom_participant, email, date_reservation, id_evenement, statut) VALUES (?, ?, ?, ?, 'en_attente')");
                if ($stmt->execute([$nom, $email, $date, $eventId])) {
                    // Message de succès sans envoyer d'email immédiatement
                    header("Location: /2A27/events?success=1&message=" . urlencode("Votre réservation a été enregistrée et est en attente de confirmation."));
                    exit;
                }
    
                throw new Exception("Erreur lors de la création de la réservation");
            } catch (Exception $e) {
                header("Location: /2A27/events?error=" . urlencode($e->getMessage()));
                exit;
            }
        }
    }

    private function sendReservationConfirmationEmail($nom, $email, $evenement) {
        $mail = new PHPMailer(true);

        try {
            // Configuration du serveur SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = $this->smtpEmail;
            $mail->Password = $this->smtpPassword;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->CharSet = 'UTF-8';

            // Configuration de l'email
            $mail->setFrom($this->smtpEmail, 'GreenMind Events');
            $mail->addAddress($email, $nom);
            $mail->addReplyTo($this->smtpEmail, 'GreenMind Events');

            // Format HTML
            $mail->isHTML(true);
            $mail->Subject = 'Confirmation de votre réservation - ' . $evenement['titre'];

            // Date formatée
            $date_formattee = date('d/m/Y', strtotime($evenement['date']));

            // Corps du message
            $mail->Body = "
                <html>
                <head>
                    <style>
                        body { font-family: Arial, sans-serif; }
                        .container { padding: 20px; max-width: 600px; margin: 0 auto; }
                        .header { background-color: #4CAF50; color: white; padding: 15px; border-radius: 5px; }
                        .content { margin: 20px 0; line-height: 1.6; }
                        .event-details { background-color: #f5f5f5; padding: 15px; border-radius: 5px; margin: 15px 0; }
                        .footer { color: #666; font-size: 12px; border-top: 1px solid #eee; padding-top: 15px; }
                    </style>
                </head>
                <body>
                    <div class='container'>
                        <div class='header'>
                            <h2>Confirmation de réservation</h2>
                        </div>
                        <div class='content'>
                            <p>Cher(e) {$nom},</p>
                            <p>Nous confirmons votre réservation pour l'événement suivant :</p>
                            <div class='event-details'>
                                <p><strong>Événement :</strong> {$evenement['titre']}</p>
                                <p><strong>Date :</strong> {$date_formattee}</p>
                                <p><strong>Lieu :</strong> {$evenement['lieu']}</p>
                            </div>
                            <p>Nous sommes ravis de vous compter parmi nos participants !</p>
                        </div>
                        <div class='footer'>
                            <p>Ceci est un message automatique, merci de ne pas y répondre.</p>
                            <p>© " . date('Y') . " GreenMind - Tous droits réservés</p>
                        </div>
                    </div>
                </body>
                </html>
            ";

            $mail->AltBody = "Confirmation de réservation\n\n" .
                           "Cher(e) {$nom},\n\n" .
                           "Nous confirmons votre réservation pour l'événement : {$evenement['titre']}\n" .
                           "Date : {$date_formattee}\n" .
                           "Lieu : {$evenement['lieu']}\n\n" .
                           "Nous sommes ravis de vous compter parmi nos participants !";

            return $mail->send();
        } catch (Exception $e) {
            error_log("Erreur lors de l'envoi du mail de réservation : " . $e->getMessage());
            return false;
        }
    }

    public function testEmailForm() {
        include 'C:\xampp1\htdocs\2A27\view\admin\pages\test-email.php';
    }

    public function testEmail() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            
            if (empty($email)) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => 'Veuillez fournir une adresse email'
                ]);
                return;
            }

            try {
                $mail = new PHPMailer(true);
                
                // Configuration SMTP avec débogage amélioré
                $mail->SMTPDebug = SMTP::DEBUG_SERVER;
                $mail->Debugoutput = function($str, $level) {
                    error_log("PHPMailer Debug: $str");
                };
                
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = $this->smtpEmail;
                $mail->Password = trim($this->smtpPassword); // Assurez-vous qu'il n'y a pas d'espaces
                $mail->SMTPSecure = 'ssl'; // Utilisation explicite de SSL
                $mail->Port = 465;
                
                // Augmenter le timeout pour les connexions lentes
                $mail->Timeout = 60;
                $mail->SMTPKeepAlive = true;
                
                // SSL Options pour le développement
                $mail->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                );
                
                $mail->CharSet = 'UTF-8';
                $mail->Encoding = 'base64';

                // Configuration email
                $mail->setFrom($this->smtpEmail, 'GreenMind Events');
                $mail->addAddress($email, 'Utilisateur');
                $mail->addReplyTo($this->smtpEmail, 'GreenMind Events');

                // Format HTML
                $mail->isHTML(true);
                $mail->Subject = 'Confirmation GreenMind';

                // Corps du message
                $mail->Body = "
                    <html>
                    <head>
                        <style>
                            body { font-family: Arial, sans-serif; }
                            .container { padding: 20px; max-width: 600px; margin: 0 auto; background-color: #f8f9fa; }
                            .header { background-color: #4CAF50; color: white; padding: 20px; border-radius: 8px 8px 0 0; text-align: center; }
                            .content { margin: 30px 0; line-height: 1.8; background-color: white; padding: 25px; border-radius: 8px; }
                            .footer { color: white; font-size: 14px; text-align: center; padding: 15px; background-color: #4CAF50; border-radius: 0 0 8px 8px; }
                            .logo { font-size: 24px; font-weight: bold; margin-bottom: 10px; }
                            .message { font-size: 18px; margin: 20px 0; color: #333; }
                            .thank-you { font-size: 20px; color: #4CAF50; font-weight: bold; margin-top: 20px; }
                        </style>
                    </head>
                    <body>
                        <div class='container'>
                            <div class='header'>
                                <div class='logo'>GreenMind Events</div>
                                <h2>Confirmation de Réservation</h2>
                            </div>
                            <div class='content'>
                                <p class='message'>Merci pour votre visite à GreenMind !</p>
                                <p class='message'>Votre réservation a été confirmée avec succès.</p>
                                <p class='thank-you'>À très bientôt !</p>
                            </div>
                            <div class='footer'>
                                <p>© " . date('Y') . " GreenMind - Tous droits réservés</p>
                            </div>
                        </div>
                    </body>
                    </html>
                ";

                $mail->AltBody = "Merci pour votre visite à GreenMind !\n\nVotre réservation est confirmée.\n\nÀ très bientôt !";

                $success = $mail->send();
                $error = $mail->ErrorInfo;
                
                // Log the result
                if ($success) {
                    error_log("Email sent successfully to: $email");
                } else {
                    error_log("Failed to send email. Error: $error");
                }
                
                header('Content-Type: application/json');
                if ($success) {
                    echo json_encode([
                        'success' => true,
                        'message' => "Email de test envoyé avec succès à $email"
                    ]);
                } else {
                    echo json_encode([
                        'success' => false,
                        'message' => "Erreur lors de l'envoi : $error"
                    ]);
                }
            } catch (Exception $e) {
                error_log("Exception lors de l'envoi de l'email : " . $e->getMessage());
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => 'Erreur : ' . $e->getMessage()
                ]);
            }
        }
    }
    
    public function confirmerReservation($id) {
        // Créer une instance du ReservationController
        require_once 'C:\xampp1\htdocs\2A27\controller\ReservationController.php';
        $reservationController = new ReservationController();

        // Appeler la méthode de confirmation
        $result = $reservationController->confirmerReservation($id);

        if ($result['success']) {
            // Rediriger avec un message de succès
            header("Location: /2A27/admin/reservations?message=" . urlencode($result['message']));
        } else {
            // Rediriger avec un message d'erreur
            header("Location: /2A27/admin/reservations?error=" . urlencode($result['message']));
        }
        exit;
    }
}
?>
