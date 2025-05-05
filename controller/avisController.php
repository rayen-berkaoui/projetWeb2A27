<?php
// controller/avisController.php

$host = 'localhost';
$dbname = 'bd_avis';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}

// ✅ Action Modifier (avec validation sécurisée)
if (isset($_POST['action']) && $_POST['action'] === 'modifier' && isset($_POST['avis_id'])) {
    $id = (int) $_POST['avis_id'];
    $contenu = trim($_POST['contenu']);
    $note = $_POST['note'];

    // Validation côté serveur
    if (strlen($contenu) < 5 || !is_numeric($note) || $note < 1 || $note > 5) {
        echo json_encode(['success' => false, 'message' => 'Données invalides.']);
        exit();
    }

    $sql = "UPDATE avis SET contenu = ?, note = ? WHERE avis_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$contenu, $note, $id]);

    echo json_encode(['success' => true, 'message' => 'Avis modifié avec succès.']);
    exit();
}

// ✅ Action Supprimer (reste comme avant)
if (isset($_GET['action']) && $_GET['action'] === 'supprimer' && isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $sql = "DELETE FROM avis WHERE avis_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);

    echo json_encode(['success' => true, 'message' => 'Avis supprimé avec succès.']);
    exit();
}
?>
