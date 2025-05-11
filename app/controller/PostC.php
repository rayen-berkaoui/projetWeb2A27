<?php

include_once 'C:\xampp\htdocs\2A27\app\models\Posts.php';

class PostC {
    private $pdo;

    public function __construct() {
        try {
            $this->pdo = new PDO("mysql:host=localhost;dbname=db_html", "root", "");
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erreur de connexion : " . $e->getMessage());
        }
    }

    public function ajouterPost(Posts $post) {
        $sql = "INSERT INTO post (titre, description, dateCreation, statut) 
                VALUES (:titre, :description, :dateCreation, :statut)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':titre' => $post->getTitre(),
            ':description' => $post->getDescription(),
            ':dateCreation' => $post->getDateCreation(),
            ':statut' => $post->getStatut()
        ]);
    }

    public function getPostById($id) {
        $sql = "SELECT * FROM post WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
