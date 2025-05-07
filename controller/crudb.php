<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../model/MailService.php';

$pdo = Database::getInstance()->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    try {
        switch ($_POST['action']) {
            case 'ajouter':
                // Validation côté serveur
                $nom = trim($_POST['nom'] ?? '');
                $prenom = trim($_POST['prenom'] ?? '');
                $email = trim($_POST['email'] ?? '');
                $contenu = trim($_POST['contenu'] ?? '');
                $note = isset($_POST['note']) ? (int)$_POST['note'] : 0;

                // Validation
                if (empty($nom) || strlen($nom) < 2) {
                    throw new Exception("Le nom doit contenir au moins 2 caractères");
                }
                if (empty($prenom) || strlen($prenom) < 2) {
                    throw new Exception("Le prénom doit contenir au moins 2 caractères");
                }
                if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    throw new Exception("L'email n'est pas valide");
                }
                if (empty($contenu) || strlen($contenu) < 5) {
                    throw new Exception("Le contenu doit contenir au moins 5 caractères");
                }
                if ($note < 1 || $note > 5) {
                    throw new Exception("La note doit être comprise entre 1 et 5");
                }

                $stmt = $pdo->prepare("
                    INSERT INTO avis (nom, prenom, email, contenu, note, is_visible, date_creation) 
                    VALUES (?, ?, ?, ?, ?, 1, NOW())
                ");
                
                $result = $stmt->execute([
                    $nom,
                    $prenom,
                    $email,
                    $contenu,
                    $note
                ]);

                if ($result) {
                    // Envoi de l'email
                    $mailService = new MailService();
                    $mailSent = $mailService->envoyerMailRemerciement(
                        $email,
                        $nom . ' ' . $prenom
                    );

                    echo json_encode([
                        'success' => true,
                        'message' => 'Avis ajouté avec succès' . ($mailSent ? ' et email envoyé' : '')
                    ]);
                } else {
                    throw new Exception("Erreur lors de l'ajout de l'avis");
                }
                break;

            case 'like':
                $commentaire_id = isset($_POST['commentaire_id']) ? (int)$_POST['commentaire_id'] : 0;
                if ($commentaire_id <= 0) {
                    throw new Exception("ID de commentaire invalide");
                }
                $stmt = $pdo->prepare("UPDATE commentaires SET likes = likes + 1 WHERE commentaire_id = ?");
                $stmt->execute([$commentaire_id]);
                echo 'OK';
                break;

            case 'dislike':
                $commentaire_id = isset($_POST['commentaire_id']) ? (int)$_POST['commentaire_id'] : 0;
                if ($commentaire_id <= 0) {
                    throw new Exception("ID de commentaire invalide");
                }
                $stmt = $pdo->prepare("UPDATE commentaires SET dislikes = dislikes + 1 WHERE commentaire_id = ?");
                $stmt->execute([$commentaire_id]);
                echo 'OK';
                break;

            case 'signaler':
                $commentaire_id = isset($_POST['commentaire_id']) ? (int)$_POST['commentaire_id'] : 0;
                if ($commentaire_id <= 0) {
                    throw new Exception("ID de commentaire invalide");
                }
                $stmt = $pdo->prepare("UPDATE commentaires SET signale = signale + 1 WHERE commentaire_id = ?");
                $stmt->execute([$commentaire_id]);
                echo 'OK';
                break;

            default:
                throw new Exception("Action invalide");
        }
    } catch (Exception $e) {
        error_log("Erreur dans crudb.php ({$_POST['action']}) : " . $e->getMessage());
        if (in_array($_POST['action'], ['like', 'dislike', 'signaler'])) {
            echo 'Erreur : ' . $e->getMessage();
        } else {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    exit;
}
?>