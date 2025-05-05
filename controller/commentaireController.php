<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=bd_avis;charset=utf8", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    exit("Erreur de connexion BDD");
}

$action = $_REQUEST['action'] ?? '';

switch ($action) {
    case 'repondre':
        // action via POST
        $cid  = (int) $_POST['commentaire_id'];
        $user = (int) $_POST['user_id']; // ou 0 si admin
        $txt  = trim($_POST['reponse']);

        // ✅ Validation manuelle
        if (strlen($txt) < 3) {
            echo json_encode(['success' => false, 'message' => 'La réponse est trop courte.']);
            exit;
        }

        $stmt = $pdo->prepare("
            INSERT INTO commentaires (avis_id, parent_id, user_id, contenu, date_creation)
            SELECT avis_id, :parent, :uid, :txt, NOW()
            FROM commentaires WHERE commentaire_id = :parent
        ");
        $stmt->execute([
            ':parent' => $cid,
            ':uid'    => $user,
            ':txt'    => $txt,
        ]);
        echo json_encode(['success' => true, 'message' => 'Réponse ajoutée.']);
        break;

    case 'signaler':
        $cid = (int) $_GET['id'];
        $stmt = $pdo->prepare("UPDATE commentaires SET signaled = 1 WHERE commentaire_id = ?");
        $stmt->execute([$cid]);
        echo json_encode(['success' => true, 'message' => 'Commentaire signalé.']);
        break;

    case 'supprimer':
        $cid = (int) $_GET['id'];
        $stmt = $pdo->prepare("DELETE FROM commentaires WHERE commentaire_id = ?");
        $stmt->execute([$cid]);
        echo json_encode(['success' => true, 'message' => 'Commentaire supprimé.']);
        break;

    default:
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Action invalide.']);
}
