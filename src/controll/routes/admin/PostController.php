<?php
require_once 'C:\xampp\htdocs\2A27\src\domain\Post.php';

class PostController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Show all posts
    public function index() {
        // Join with comments table to show comment content
        $query = "SELECT post.*, commentaires.content AS commentaire_content, commentaires.author AS commentaire_author
                  FROM post
                  LEFT JOIN commentaires ON post.id = commentaires.post_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC); // Use fetchAll for PDO

        include 'C:\xampp\htdocs\2A27\view\admin\pages\post\list.php';
    }

    // Show the create form and process form submission
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

            // Redirect to posts list
            header("Location: /2A27/admin/posts");
            exit;
        }

        include 'C:\xampp\htdocs\2A27\view\admin\pages\post\create.php';
    }

    // Show the edit form
    public function edit($id) {
        $query = "SELECT * FROM post WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->execute();
        $post = $stmt->fetch(PDO::FETCH_ASSOC);

        include 'C:\xampp\htdocs\2A27\view\admin\pages\post\edit.php';
    }

    // Update the post
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

            header("Location: /2A27/admin/posts");
            exit;
        }
    }

    // Delete a post
    public function delete($id) {
        $query = "DELETE FROM post WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->execute();

        header("Location: /2A27/admin/posts");
        exit;
    }

    // Create a comment for a post
    public function createComment($postId) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $content = $_POST['content'];
            $author = $_POST['author'];

            $query = "INSERT INTO commentaires (post_id, content, author) VALUES (?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(1, $postId, PDO::PARAM_INT);
            $stmt->bindParam(2, $content, PDO::PARAM_STR);
            $stmt->bindParam(3, $author, PDO::PARAM_STR);
            $stmt->execute();

            header("Location: /2A27/admin/posts/$postId");
            exit;
        }

        include 'C:\xampp\htdocs\2A27\view\admin\pages\post\createcomment.php';
    }

    // Delete a comment
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