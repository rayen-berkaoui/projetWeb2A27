<?php
require_once '../model/MailService.php';

try {
    $pdo = new PDO("mysql:host=localhost;dbname=bd_avis;charset=utf8", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// 1) Gestion du Like
if (isset($_POST['action']) && $_POST['action'] === 'like' && isset($_POST['commentaire_id'])) {
    $commentaireId = (int)$_POST['commentaire_id'];

    $stmt = $pdo->prepare("UPDATE commentaires SET likes = likes + 1 WHERE commentaire_id = :id");
    if ($stmt->execute([':id' => $commentaireId])) {
        echo "OK";
    } else {
        echo "Erreur";
    }
    exit;
}

// 2) Gestion du Dislike
if (isset($_POST['action']) && $_POST['action'] === 'dislike' && isset($_POST['commentaire_id'])) {
    $commentaireId = (int)$_POST['commentaire_id'];

    $stmt = $pdo->prepare("UPDATE commentaires SET dislikes = dislikes + 1 WHERE commentaire_id = :id");
    if ($stmt->execute([':id' => $commentaireId])) {
        echo "OK";
    } else {
        echo "Erreur";
    }
    exit;
}

// 3) Gestion du signalement
if (isset($_POST['action']) && $_POST['action'] === 'signaler' && isset($_POST['commentaire_id'])) {
    $commentaireId = (int)$_POST['commentaire_id'];

    // Mettre à jour la table des commentaires pour marquer le commentaire comme signalé
    $stmt = $pdo->prepare("UPDATE commentaires SET signaled = 1 WHERE commentaire_id = :id");
    if ($stmt->execute([':id' => $commentaireId])) {
        echo "OK";  // Si tout est bon, retour "OK"
    } else {
        echo "Erreur";  // Si une erreur survient
    }
    exit;
}

include '../model/db.php'; // Connexion PDO toujours disponible

// Ajouter un avis + un premier commentaire
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'ajouter') {
    if (isset($_POST['user_id'], $_POST['contenu'], $_POST['note'])) {
        $user_id = htmlspecialchars($_POST['user_id']);
        $email = $_POST['email'] ?? null;
        $contenu = htmlspecialchars($_POST['contenu']);
        $note = htmlspecialchars($_POST['note']);

        try {
            // Insertion dans avis
            $stmt = $pdo->prepare("INSERT INTO avis (user_id, email, contenu, note, date_creation) VALUES (?, ?, ?, ?, NOW())");
            $stmt->execute([$user_id, $email, $contenu, $note]);

            $avis_id = $pdo->lastInsertId();

            // Insertion dans commentaires
            $stmt2 = $pdo->prepare("INSERT INTO commentaires (avis_id, user_id, contenu, date_creation) VALUES (?, ?, ?, NOW())");
            $stmt2->execute([$avis_id, $user_id, $contenu]);

            // ✅ Envoi de l'email de remerciement
            if ($email) {
                require_once '../model/MailService.php';
                MailService::envoyerMailRemerciement($email, $user_id);
            }

            echo "Success";
        } catch (PDOException $e) {
            echo "Erreur DB: " . $e->getMessage();
        }
    } else {
        echo "Champs manquants";
    }
    exit;
}


// Ajouter un Like
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'like') {
    $commentaire_id = (int) $_POST['commentaire_id'];
    $stmt = $pdo->prepare("UPDATE commentaires SET likes = likes + 1 WHERE commentaire_id = ?");
    if ($stmt->execute([$commentaire_id])) {
        echo 'OK';
    } else {
        echo 'Erreur';
    }
    exit;
}

// Ajouter un Dislike
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'dislike') {
    $commentaire_id = (int) $_POST['commentaire_id'];
    $stmt = $pdo->prepare("UPDATE commentaires SET dislikes = dislikes + 1 WHERE commentaire_id = ?");
    if ($stmt->execute([$commentaire_id])) {
        echo 'OK';
    } else {
        echo 'Erreur';
    }
    exit;
}
?>
