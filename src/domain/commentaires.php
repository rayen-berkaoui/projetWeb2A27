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
            return ['error' => 'Erreur lors de la récupération des commentaires : ' . $e->getMessage()];
        }
    }

    // Récupère un commentaire par ID
    public function find($id) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM commentaires WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ['error' => 'Erreur lors de la récupération du commentaire : ' . $e->getMessage()];
        }
    }

    // Crée un nouveau commentaire
    public function create($postId, $content, $author) {
        // Validation des données
        if (empty($content) || empty($author)) {
            return ['error' => 'Le contenu et l\'auteur sont obligatoires.'];
        }

        try {
            // Préparation de la requête d'insertion
            $stmt = $this->pdo->prepare("INSERT INTO commentaires (post_id, content, author) VALUES (?, ?, ?)");
            $stmt->execute([$postId, $content, $author]);
            return ['success' => 'Commentaire ajouté avec succès.'];
        } catch (PDOException $e) {
            return ['error' => 'Erreur lors de l\'ajout du commentaire : ' . $e->getMessage()];
        }
    }

    // Met à jour un commentaire
    public function update($id, $content, $author) {
        // Validation des données
        if (empty($content) || empty($author)) {
            return ['error' => 'Le contenu et l\'auteur sont obligatoires.'];
        }

        try {
            $stmt = $this->pdo->prepare("UPDATE commentaires SET content = ?, author = ? WHERE id = ?");
            $stmt->execute([$content, $author, $id]);
            return ['success' => 'Commentaire mis à jour avec succès.'];
        } catch (PDOException $e) {
            return ['error' => 'Erreur lors de la mise à jour du commentaire : ' . $e->getMessage()];
        }
    }

    // Supprime un commentaire
    public function delete($id) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM commentaires WHERE id = ?");
            $stmt->execute([$id]);
            return ['success' => 'Commentaire supprimé avec succès.'];
        } catch (PDOException $e) {
            return ['error' => 'Erreur lors de la suppression du commentaire : ' . $e->getMessage()];
        }
    }

    // Récupère les commentaires d'un post spécifique
    public function findByPostId($postId) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM commentaires WHERE post_id = ? ORDER BY id DESC");
            $stmt->execute([$postId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ['error' => 'Erreur lors de la récupération des commentaires du post : ' . $e->getMessage()];
        }
    }
}
?>
