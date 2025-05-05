<?php
// controller/ajouter_commentaire.php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=bd_avis;charset=utf8","root","");
    $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    exit("Erreur BDD");
}

if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['avis_id'],$_POST['user_id'],$_POST['contenu'])) {
    $stmt = $pdo->prepare(
      "INSERT INTO commentaires (avis_id, user_id, contenu) 
       VALUES (:avis, :user, :txt)"
    );
    $stmt->execute([
      ':avis' => (int)$_POST['avis_id'],
      ':user' => (int)$_POST['user_id'],
      ':txt'  => trim($_POST['contenu'])
    ]);
    echo "OK";
} else {
    http_response_code(400);
    echo "Données manquantes";
}
