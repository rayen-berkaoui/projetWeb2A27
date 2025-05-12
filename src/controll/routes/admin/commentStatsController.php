<?php
// commentStatsController.php

$host = 'localhost';
$dbname = 'db_html';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
    exit;
}

// Récupérer toutes les statistiques des commentaires
$sql = "SELECT contenu, likes, dislikes FROM commentaires";
$stmt = $pdo->query($sql);
$commentStats = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
