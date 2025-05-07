<?php
require_once __DIR__ . '/../model/Evenement.php';

// PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once __DIR__ . '/../view/vendors/PHPMailer/PHPMailer-master/src/Exception.php';
require_once __DIR__ . '/../view/vendors/PHPMailer/PHPMailer-master/src/PHPMailer.php';
require_once __DIR__ . '/../view/vendors/PHPMailer/PHPMailer-master/src/SMTP.php';


class EvenementController {
    private $evenementModel;
    private $smtpEmail = 'sabsoubbdziri@gmail.com';
    private $smtpPassword = 'aohe vapi fipx sxdh'; // Nouveau mot de passe d'application sans espaces

    public function __construct() {
        $this->evenementModel = new Evenement();
    }

    public function index() {
        $evenements = $this->evenementModel->getAll();
        require_once __DIR__ . '/../view/evenements/list.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titre = $_POST['titre'] ?? '';
            $description = $_POST['description'] ?? '';
            $date_evenement = $_POST['date_evenement'] ?? '';
            $lieu = $_POST['lieu'] ?? '';
            $admin_email = $_POST['admin_email'] ?? '';

            if ($this->evenementModel->add($titre, $description, $date_evenement, $lieu)) {
                // Envoi de l'email de confirmation avec l'email saisi
                $this->envoyerEmailConfirmation($titre, $date_evenement, $admin_email);
                header('Location: /evenements');
                exit;
            }
        }
        require_once __DIR__ . '/../view/evenements/create.php';
    }

    public function edit($id) {
        $evenement = $this->evenementModel->getById($id);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titre = $_POST['titre'] ?? '';
            $description = $_POST['description'] ?? '';
            $date_evenement = $_POST['date_evenement'] ?? '';
            $lieu = $_POST['lieu'] ?? '';

            $data = [
                'titre' => $titre,
                'description' => $description,
                'date_evenement' => $date_evenement,
                'lieu' => $lieu
            ];

            if ($this->evenementModel->update($id, $data)) {
                header('Location: /evenements');
                exit;
            }
        }
        require_once __DIR__ . '/../view/evenements/edit.php';
    }

    public function delete($id) {
        if ($this->evenementModel->delete($id)) {
            header('Location: /evenements');
            exit;
        }
    }

    public function testEmail() {
        try {
            $resultat = $this->envoyerEmailConfirmation(
                "Test Email - " . date('Y-m-d H:i:s'),
                date('Y-m-d'),
                'selminaama73@gmail.com'
            );
            
            if ($resultat) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Email envoyé avec succès'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Échec de l\'envoi de l\'email'
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ]);
        }
    }

    // ✅ Méthode privée pour envoyer l'email
    private function envoyerEmailConfirmation($titre, $date_evenement, $admin_email) {
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
            
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';

            // Configuration de l'expéditeur et du destinataire
            $mail->setFrom($this->smtpEmail, 'GreenMind Events');
            $mail->addAddress($admin_email, 'Admin GreenMind');
            $mail->addReplyTo($this->smtpEmail, 'GreenMind Events');

            // Configuration du format
            $mail->isHTML(true);
            $mail->Subject = 'Confirmation - Nouvel événement créé';

            // Formatage de la date pour l'affichage
            $date_formattee = date('d/m/Y', strtotime($date_evenement));

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
                            <h2>Confirmation de création d'événement</h2>
                        </div>
                        <div class='content'>
                            <p>Bonjour,</p>
                            <p>Un nouvel événement a été créé avec succès dans votre système GreenMind.</p>
                            <div class='event-details'>
                                <p><strong>Titre de l'événement :</strong> {$titre}</p>
                                <p><strong>Date de l'événement :</strong> {$date_formattee}</p>
                            </div>
                            <p>Vous pouvez consulter les détails de cet événement dans votre espace administrateur.</p>
                        </div>
                        <div class='footer'>
                            <p>Ceci est un message automatique, merci de ne pas y répondre.</p>
                            <p>© " . date('Y') . " GreenMind - Tous droits réservés</p>
                        </div>
                    </div>
                </body>
                </html>
            ";

            // Version texte pour les clients qui ne supportent pas l'HTML
            $mail->AltBody = "Nouvel événement créé : {$titre}\nDate : {$date_formattee}\n\nCeci est un message automatique.";

            // Envoi de l'email
            if($mail->send()) {
                error_log("Email envoyé avec succès à {$admin_email}");
                return true;
            } else {
                error_log("Échec de l'envoi de l'email : " . $mail->ErrorInfo);
                return false;
            }
        } catch (Exception $e) {
            error_log("Erreur lors de l'envoi du mail : " . $e->getMessage());
            error_log("Détails de l'erreur SMTP : " . $mail->ErrorInfo);
            return false;
        }
    }
}
