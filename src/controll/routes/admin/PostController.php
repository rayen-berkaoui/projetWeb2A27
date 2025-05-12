<?php
require_once 'C:\xampp\htdocs\lezm\src\domain\Post.php';

class PostController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Afficher tous les posts avec leurs commentaires
    public function index() {
        $query = "SELECT post.*, commentaires.content AS commentaire_content, commentaires.auteur AS commentaire_auteur
                  FROM post
                  LEFT JOIN commentaires ON post.id = commentaires.post_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        include 'C:\xampp\htdocs\lezm\view\admin\pages\post\list.php';
    }

    // Afficher le formulaire de création et traiter la soumission
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titre = $_POST['titre'];
            $description = $_POST['description'];
            $statut = $_POST['statut'];
            $auteurs = isset($_POST['auteur']) && is_array($_POST['auteur']) ? $_POST['auteur'] : [];
            $contents = isset($_POST['content']) && is_array($_POST['content']) ? $_POST['content'] : [];

            try {
                // Start a transaction
                $this->db->beginTransaction();

                // Insert the post
                $query = "INSERT INTO post (titre, description, statut, dateCreation) VALUES (?, ?, ?, NOW())";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(1, $titre, PDO::PARAM_STR);
                $stmt->bindParam(2, $description, PDO::PARAM_STR);
                $stmt->bindParam(3, $statut, PDO::PARAM_STR);
                $stmt->execute();

                // Get the newly created post ID
                $postId = $this->db->lastInsertId();

                // Insert comments if provided
                for ($i = 0; $i < count($auteurs); $i++) {
                    $auteur = trim($auteurs[$i]);
                    $content = trim($contents[$i]);
                    if ($auteur !== '' && $content !== '') {
                        $query = "INSERT INTO commentaires (post_id, auteur, content, date_creation) VALUES (?, ?, ?, NOW())";
                        $stmt = $this->db->prepare($query);
                        $stmt->bindParam(1, $postId, PDO::PARAM_INT);
                        $stmt->bindParam(2, $auteur, PDO::PARAM_STR);
                        $stmt->bindParam(3, $content, PDO::PARAM_STR);
                        $stmt->execute();
                    }
                }

                // Commit the transaction
                $this->db->commit();

                // Redirection vers la liste des posts
                header("Location: /lezm/admin/posts");
                exit;
            } catch (Exception $e) {
                // Roll back the transaction on error
                $this->db->rollBack();
                die("Erreur lors de la création : " . $e->getMessage());
            }
        }

        include 'C:\xampp\htdocs\lezm\view\admin\pages\post\create.php';
    }

    // Afficher le formulaire d'édition
    public function edit($id) {
        $query = "SELECT * FROM post WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->execute();
        $post = $stmt->fetch(PDO::FETCH_ASSOC);

        include 'C:\xampp\htdocs\lezm\view\admin\pages\post\edit.php';
    }

    // Mettre à jour un post
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

            header("Location: /lezm/admin/posts");
            exit;
        }
    }

    // Supprimer un post
    public function delete($id) {
        $query = "DELETE FROM post WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->execute();

        header("Location: /lezm/admin/posts");
        exit;
    }
}
?>