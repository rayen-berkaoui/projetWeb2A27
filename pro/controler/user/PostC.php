<?php
require_once 'C:\xampp\htdocs\lezm\config.php'; // adapte le chemin si besoin

class PostC {
    // Ajouter un post
    public function ajouterPost($post) {
        $sql = "INSERT INTO post (titre, description, statut) VALUES (:titre, :description, :statut)";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':titre', $post['titre']);
            $query->bindValue(':description', $post['description']);
            $query->bindValue(':statut', $post['statut']);
            $query->execute();
        } catch (PDOException $e) {
            echo 'Erreur lors de l\'ajout : ' . $e->getMessage();
        }
    }

    // Supprimer un post
    public function supprimerPost($id) {
        $sql = "DELETE FROM post WHERE id = :id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':id', $id, PDO::PARAM_INT);
            $query->execute();
        } catch (PDOException $e) {
            echo 'Erreur lors de la suppression : ' . $e->getMessage();
        }
    }

    // Modifier un post
    public function modifierPost($id, $post) {
        $sql = "UPDATE post SET titre = :titre, description = :description, statut = :statut WHERE id = :id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':titre', $post['titre']);
            $query->bindValue(':description', $post['description']);
            $query->bindValue(':statut', $post['statut']);
            $query->bindValue(':id', $id, PDO::PARAM_INT);
            $query->execute();
        } catch (PDOException $e) {
            echo 'Erreur lors de la modification : ' . $e->getMessage();
        }
    }

    // Chercher des posts par titre
    public function chercherPosts($search) {
        $sql = "SELECT * FROM post WHERE titre LIKE :search";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':search', '%' . $search . '%');
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo 'Erreur lors de la recherche : ' . $e->getMessage();
        }
    }

    // Lister tous les posts triés par ID (ASC ou DESC)
    public function getAllPostsSortedById($order = 'ASC') {
        $order = strtoupper($order);
        if ($order !== 'ASC' && $order !== 'DESC') {
            $order = 'ASC'; // sécurité si jamais l'utilisateur envoie n'importe quoi
        }
        $sql = "SELECT * FROM post ORDER BY id $order";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo 'Erreur lors de l\'affichage : ' . $e->getMessage();
        }
    }
}
?>
