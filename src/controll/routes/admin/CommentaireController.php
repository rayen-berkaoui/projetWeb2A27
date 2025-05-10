<?php

class CommentaireController
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // Affiche le formulaire de création
    public function create($postId = null)
    {
        // Si un post_id est fourni, on le passe au formulaire
        $postId = $postId ? $postId : ''; 
        include_once 'C:\xampp\htdocs\2A27\view\admin\pages\commentaire\createcomment.php';
    }

    // Stocke un commentaire après soumission
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données du formulaire
            $content = $_POST['content'];
            $post_id = $_POST['post_id'];
            

            // Validation des données
            if (empty($content) || empty($post_id) || empty($auteur)) {
                echo "Erreur : Tous les champs sont requis.";
                exit;
            }

            // Requête d'insertion dans la base de données
            $query = "INSERT INTO commentaires (content, post_id, auteur) VALUES (?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(1, $content, PDO::PARAM_STR);
            $stmt->bindParam(2, $post_id, PDO::PARAM_INT);
            $stmt->bindParam(3, $auteur, PDO::PARAM_STR);  // Ajout de l'auteur

            // Exécution de la requête
            if ($stmt->execute()) {
                // Redirection après l'insertion réussie
                header("Location:C:\xampp\htdocs\2A27\view\admin\pages\commentaire\createcomment.php");
                exit;
            } else {
                echo "Erreur lors de l'ajout du commentaire.";
            }
        } else {
            echo "Méthode non autorisée.";
        }
    }

    // Liste tous les commentaires
    public function list()
    {
        $query = "SELECT * FROM commentaires";
        $stmt = $this->db->query($query);
        $commentaires = $stmt->fetchAll(PDO::FETCH_ASSOC);

        include_once 'C:\xampp\htdocs\2A27\view\admin\pages\commentaire\listcomment.php';
    }

    // Affiche le formulaire d'édition
    public function edit($id)
    {
        $query = "SELECT * FROM commentaires WHERE post_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->execute();
        $commentaire = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$commentaire) {
            echo "Commentaire non trouvé.";
            exit;
        }

        include_once 'C:\xampp\htdocs\2A27\view\admin\pages\commentaire\editcomment.php';
    }

    // Mise à jour d'un commentaire
    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $content = $_POST['content'];

            if (empty($content)) {
                echo "Erreur : Le contenu ne doit pas être vide.";
                exit;
            }

            // Requête de mise à jour
            $query = "UPDATE commentaires SET content = ? WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(1, $content, PDO::PARAM_STR);
            $stmt->bindParam(2, $id, PDO::PARAM_INT);

            // Exécution de la requête
            if ($stmt->execute()) {
                header("Location: /2A27/admin/commentaire"); // Rediriger après la mise à jour
                exit;
            } else {
                echo "Erreur lors de la mise à jour du commentaire.";
            }
        } else {
            echo "Méthode non autorisée.";
        }
    }

    // Supprimer un commentaire
    public function delete($id)
    {
        $query = "DELETE FROM commentaires WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->execute();

        // Redirection vers la liste des commentaires après suppression
        header("Location: /2A27/admin/commentaire/list");
        exit;
    }
}
?>
