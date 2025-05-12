<?php

include_once 'C:/xampp/htdocs/lezm/src/controll/process/config.php';  // Connexion à la base de données
include_once 'C:\xampp\htdocs\lezm\app\models\Commentaire.php';  // Inclusion de la classe Commentaire

class CommentaireC
{
    private $conn;

    // Constructeur pour initialiser la connexion à la base de données
    public function __construct()
    {
        $this->conn = db::getConnexion();
    }

    // Méthode pour ajouter un commentaire dans la base de données
    public function addCommentaire($commentaire)
    {
        // Requête SQL pour insérer un commentaire
        $sql = "INSERT INTO commentaires (post_id, content, author, timestamp) VALUES (:post_id, :content, :author, :timestamp)";
        $stmt = $this->conn->prepare($sql);

        // Lier les paramètres pour éviter les injections SQL
        $stmt->bindParam(':post_id', $commentaire->getPostId());
        $stmt->bindParam(':content', $commentaire->getContent());
        $stmt->bindParam(':author', $commentaire->getAuthor());
        $stmt->bindParam(':timestamp', $commentaire->getTimestamp());

        // Exécution de la requête et retour du résultat
        return $stmt->execute();
    }

    // Méthode pour récupérer les commentaires d'un post spécifique
    public function getCommentairesByPostId($postId)
    {
        $sql = "SELECT * FROM commentaires WHERE post_id = :post_id";
        $stmt = $this->conn->prepare($sql);

        // Lier le post_id
        $stmt->bindParam(':post_id', $postId);
        $stmt->execute();

        // Récupérer et retourner les résultats
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
