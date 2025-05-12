<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=db_html;charset=utf8", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    error_log("Erreur de connexion : " . $e->getMessage());
    http_response_code(500);
    exit("Erreur de connexion à la base de données");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' 
    && isset($_POST['avis_id'], $_POST['nom'], $_POST['prenom'], $_POST['contenu'])) {

    try {
        $stmt = $pdo->prepare("
            INSERT INTO commentaires (avis_id, nom, prenom, contenu, likes, dislikes, signale, is_visible, is_admin, date_creation)
            VALUES (:avis, :nom, :prenom, :contenu, 0, 0, 0, 1, 0, NOW())
        ");

        $stmt->execute([
            ':avis'    => (int)$_POST['avis_id'],
            ':nom'     => trim($_POST['nom']),
            ':prenom'  => trim($_POST['prenom']),
            ':contenu' => trim($_POST['contenu']),
        ]);

        echo "OK";
    } catch (PDOException $e) {
        error_log("Erreur SQL (ajout commentaire) : " . $e->getMessage());
        http_response_code(500);
        echo "Erreur SQL : " . $e->getMessage();
    }
} else {
    http_response_code(400);
    echo "Données manquantes";
}
?>