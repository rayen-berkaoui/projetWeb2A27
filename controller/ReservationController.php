<?php
require_once __DIR__ . '/../model/Reservation.php';
require_once __DIR__ . '/../model/Evenement.php';

// PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once __DIR__ . '/../view/vendors/PHPMailer/PHPMailer-master/src/Exception.php';
require_once __DIR__ . '/../view/vendors/PHPMailer/PHPMailer-master/src/PHPMailer.php';
require_once __DIR__ . '/../view/vendors/PHPMailer/PHPMailer-master/src/SMTP.php';

class ReservationController {
    private $reservationModel;
    private $evenementModel;
    private $smtpEmail = 'amaniltaifi02@gmail.com';
    private $smtpPassword = 'zcij nscr ehle fosj';

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

            // Créer la réservation avec statut en attente
            $id_reservation = $this->reservationModel->create($data);
            
            return [
                'success' => true,
                'message' => 'Réservation enregistrée avec succès. En attente de confirmation.',
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

    // Nouvelle méthode pour confirmer une réservation
    public function confirmerReservation($id_reservation) {
        try {
            // Récupérer les détails de la réservation
            $reservation = $this->reservationModel->getById($id_reservation);
            if (!$reservation) {
                throw new Exception("Réservation non trouvée");
            }

            // Vérifier si la réservation n'est pas déjà confirmée
            if ($reservation['statut'] === 'confirmee') {
                throw new Exception("Cette réservation est déjà confirmée");
            }

            // Mettre à jour le statut
            $this->reservationModel->updateStatut($id_reservation, 'confirmee');
            
            // Récupérer les détails de l'événement
            $evenement = $this->evenementModel->getById($reservation['id_evenement']);
            
            // Envoyer l'email de confirmation au participant
            $this->envoyerEmailConfirmationReservation($reservation['nom_participant'], $reservation['email'], $evenement);
            
            // Envoyer l'email de confirmation à l'administrateur
            $this->envoyerEmailConfirmationAdmin($reservation['nom_participant'], $reservation['email'], $evenement);
            
            return [
                'success' => true,
                'message' => 'Réservation confirmée avec succès'
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    private function envoyerEmailConfirmationReservation($nom, $email, $evenement) {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->SMTPDebug = 3; // Debug détaillé
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
            
            // SSL Options for development
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            
            // Character encoding
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';

            // Configuration de l'email
            $mail->setFrom($this->smtpEmail, 'GreenMind Events');
            $mail->addAddress($email, $nom);
            $mail->addReplyTo($this->smtpEmail, 'GreenMind Events');

            // Format HTML
            $mail->isHTML(true);
            $mail->Subject = 'Confirmation de votre réservation - ' . $evenement['titre'];

            // Date formatée pour l'affichage
            $date_formattee = date('d/m/Y', strtotime($evenement['date_evenement']));

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
                            <h2>Confirmation de votre réservation</h2>
                        </div>
                        <div class='content'>
                            <p>Cher(e) {$nom},</p>
                            <p>Nous avons le plaisir de confirmer votre réservation pour l'événement suivant :</p>
                            <div class='event-details'>
                                <p><strong>Événement :</strong> {$evenement['titre']}</p>
                                <p><strong>Date :</strong> {$date_formattee}</p>
                                <p><strong>Lieu :</strong> {$evenement['lieu']}</p>
                                <p><strong>Description :</strong> {$evenement['description']}</p>
                            </div>
                            <p>Nous sommes ravis de vous compter parmi nos participants !</p>
                            <p>Un rappel vous sera envoyé quelques jours avant l'événement.</p>
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
                           "Lieu : {$evenement['lieu']}\n" .
                           "Description : {$evenement['description']}\n\n" .
                           "Nous sommes ravis de vous compter parmi nos participants !\n\n" .
                           "Un rappel vous sera envoyé quelques jours avant l'événement.";

            if(!$mail->send()) {
                throw new Exception('Erreur lors de l\'envoi de l\'email: ' . $mail->ErrorInfo);
            }
            return true;

        } catch (Exception $e) {
            error_log("Erreur lors de l'envoi du mail de réservation : " . $e->getMessage());
            throw new Exception("Erreur lors de l'envoi de l'email de confirmation: " . $e->getMessage());
        }
    }

    private function envoyerEmailConfirmationAdmin($nom_participant, $email_participant, $evenement) {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = $this->smtpEmail;
            $mail->Password = $this->smtpPassword;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;
            
            // SSL Options for development
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            
            // Character encoding
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';

            // Configuration de l'email
            $mail->setFrom($this->smtpEmail, 'GreenMind Events');
            $mail->addAddress($this->smtpEmail, 'Admin GreenMind');
            $mail->addReplyTo($this->smtpEmail, 'GreenMind Events');

            // Date formatée pour l'affichage
            $date_formattee = date('d/m/Y', strtotime($evenement['date_evenement']));

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
                            <h2>Nouvelle réservation confirmée</h2>
                        </div>
                        <div class='content'>
                            <p>Une nouvelle réservation a été effectuée :</p>
                            <div class='event-details'>
                                <p><strong>Participant :</strong> {$nom_participant}</p>
                                <p><strong>Email du participant :</strong> {$email_participant}</p>
                                <p><strong>Événement :</strong> {$evenement['titre']}</p>
                                <p><strong>Date :</strong> {$date_formattee}</p>
                                <p><strong>Lieu :</strong> {$evenement['lieu']}</p>
                            </div>
                            <p>La réservation a été enregistrée avec succès.</p>
                        </div>
                        <div class='footer'>
                            <p>© " . date('Y') . " GreenMind - Tous droits réservés</p>
                        </div>
                    </div>
                </body>
                </html>
            ";

            $mail->AltBody = "Nouvelle réservation confirmée\n\n" .
                           "Participant : {$nom_participant}\n" .
                           "Email : {$email_participant}\n" .
                           "Événement : {$evenement['titre']}\n" .
                           "Date : {$date_formattee}\n" .
                           "Lieu : {$evenement['lieu']}\n\n" .
                           "La réservation a été enregistrée avec succès.";

            if(!$mail->send()) {
                throw new Exception('Erreur lors de l\'envoi de l\'email: ' . $mail->ErrorInfo);
            }
            return true;

        } catch (Exception $e) {
            error_log("Erreur lors de l'envoi du mail d'administration : " . $e->getMessage());
            throw new Exception("Erreur lors de l'envoi de l'email d'administration: " . $e->getMessage());
        }
    }
}