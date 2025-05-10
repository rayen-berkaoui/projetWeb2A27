<?php
class Commentaires {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Récupère tous les commentaires
    public function all() {
        try {
            $stmt = $this->pdo->query("SELECT * FROM commentaires ORDER BY id DESC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error fetching all comments: ' . $e->getMessage());
            return ['error' => 'An error occurred while fetching comments. Please try again later.'];
        }
    }

    // Récupère un commentaire par ID
    public function find($id) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM commentaires WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error fetching comment by ID: ' . $e->getMessage());
            return ['error' => 'An error occurred while fetching the comment. Please try again later.'];
        }
    }

    // Crée un nouveau commentaire
    public function create($postId, $content, $author) {
        // Validation des données
        if (empty($content) || empty($author)) {
            return ['error' => 'Content and author are required.'];
        }

        try {
            // Préparation de la requête d'insertion
            $stmt = $this->pdo->prepare("INSERT INTO commentaires (post_id, content, author) VALUES (?, ?, ?)");
            $stmt->execute([$postId, $content, $author]);
            return ['success' => 'Comment added successfully.'];
        } catch (PDOException $e) {
            error_log('Error adding comment: ' . $e->getMessage());
            return ['error' => 'An error occurred while adding the comment. Please try again later.'];
        }
    }

    // Met à jour un commentaire
    public function update($id, $content, $author) {
        // Validation des données
        if (empty($content) || empty($author)) {
            return ['error' => 'Content and author are required.'];
        }

        try {
            $stmt = $this->pdo->prepare("UPDATE commentaires SET content = ?, author = ? WHERE id = ?");
            $stmt->execute([$content, $author, $id]);
            return ['success' => 'Comment updated successfully.'];
        } catch (PDOException $e) {
            error_log('Error updating comment: ' . $e->getMessage());
            return ['error' => 'An error occurred while updating the comment. Please try again later.'];
        }
    }

    // Supprime un commentaire
    public function delete($id) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM commentaires WHERE id = ?");
            $stmt->execute([$id]);
            return ['success' => 'Comment deleted successfully.'];
        } catch (PDOException $e) {
            error_log('Error deleting comment: ' . $e->getMessage());
            return ['error' => 'An error occurred while deleting the comment. Please try again later.'];
        }
    }

    // Récupère les commentaires d'un post spécifique
    public function findByPostId($postId) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM commentaires WHERE post_id = ? ORDER BY id DESC");
            $stmt->execute([$postId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error fetching comments by post ID: ' . $e->getMessage());
            return ['error' => 'An error occurred while fetching comments. Please try again later.'];
        }
    }
}
?>
