<?php
require_once 'C:\xampp\htdocs\2A27\src\domain\Post.php';
require_once 'C:\xampp\htdocs\2A27\src\domain\Commentaire.php';

class PostController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Afficher tous les posts
    public function index() {
        // Joindre la table commentaires pour afficher le contenu des commentaires
        $query = "SELECT post.*, commentaires.content AS commentaire_content 
                  FROM post 
                  LEFT JOIN commentaires ON post.id = commentaires.post_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC); // Utiliser fetchAll pour récupérer tous les résultats
        
        include 'C:\xampp\htdocs\2A27\view\admin\pages\posts\list.php'; 
    }

    // Afficher le formulaire de création et traiter la soumission
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titre = $_POST['titre'];
            $description = $_POST['description'];
            $statut = $_POST['statut'];

            $query = "INSERT INTO post (titre, description, statut, dateCreation) VALUES (?, ?, ?, NOW())";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(1, $titre, PDO::PARAM_STR);
            $stmt->bindParam(2, $description, PDO::PARAM_STR);
            $stmt->bindParam(3, $statut, PDO::PARAM_STR);
            $stmt->execute();

            header("Location: /2A27/admin/posts");
            exit;
        }

        include 'C:\xampp\htdocs\2A27\view\admin\pages\posts\create.php';
    }

    // Afficher le formulaire d'édition d'un post
    public function edit($id) {
        $query = "SELECT * FROM post WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->execute();
        $post = $stmt->fetch(PDO::FETCH_ASSOC); // Utiliser fetch pour une seule ligne

        include 'C:\xampp\htdocs\2A27\view\admin\pages\posts\edit.php';
    }

    // Mettre à jour un post
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $titre = $_POST['titre'];
            $description = $_POST['description'];
            $statut = $_POST['statut'];

            $query = "UPDATE post SET titre = ?, description = ?, statut = ? WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(1, $titre, PDO::PARAM_STR);
            $stmt->bindParam(2, $description, PDO::PARAM_STR);
            $stmt->bindParam(3, $statut, PDO::PARAM_STR);
            $stmt->bindParam(4, $id, PDO::PARAM_INT);
            $stmt->execute();

            header("Location: /2A27/admin/posts");
            exit;
        }
    }

    // Supprimer un post
    public function delete($id) {
        $query = "DELETE FROM post WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->execute();

        header("Location: /2A27/admin/posts");
        exit;
    }

    // Créer un commentaire pour un post
    public function createComment($postId) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $content = $_POST['content'];

            $query = "INSERT INTO commentaires (post_id, content) VALUES (?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(1, $postId, PDO::PARAM_INT);
            $stmt->bindParam(2, $content, PDO::PARAM_STR);
            $stmt->execute();

            header("Location: /2A27/admin/posts/$postId");
            exit;
        }

        include 'C:\xampp\htdocs\2A27\view\admin\pages\posts\createComment.php';
    }

    // Supprimer un commentaire
    public function deleteComment($id) {
        $query = "DELETE FROM commentaires WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->execute();

        header("Location: /2A27/admin/posts");
        exit;
    }
}
?>
