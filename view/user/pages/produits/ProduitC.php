<?php
require_once __DIR__ . '/../../../../src/controll/routes/config.php';


require_once "Produit.php";


class ProduitC {
    public static function getProduits () {
        $sql = "select p.*, c.nom as cnom, c.photo as cimg from produit p, categorieProduit c where c.id = p.categorie";
   
        $db = config::getConnexion();
        try {
            $result = $db->query($sql);
            return $result;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }




    public static function getProduitss()
{
    $sql = "SELECT p.*, c.nom as cnom, c.photo as cimg FROM produit p JOIN categorieProduit c ON c.id = p.categorie";
    $db = config::getConnexion();
    try {
        $result = $db->query($sql);
        $produits = [];
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $produits[] = new Produit(
                $row['id'],
                $row['cnom'],       // catégorie
                $row['nom'],
                $row['marque'],
                $row['materiel'],
                $row['prix'],
                $row['stock'],
                $row['pays'],
                $row['cimg']        // photo
            );
        }
        return $produits;
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

    public static function getProduitsFromId($id)
    {
        $sql = "select p.*, c.nom as cnom, c.photo as cimg from produit p, categorieProduit c where c.id = p.categorie and p.id = :id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(":id", $id);
            $query->execute();

            $result = $query->fetch(PDO::FETCH_ASSOC);
            $p = new Produit($id, $result['cnom'], $result['nom'], $result['marque'], $result['materiel'], $result['prix'], $result['stock'], $result['pays'], $result['cimg']);
            return $p;

        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
     public static function supprimerProduit($id)
{
    $sql = "DELETE FROM produit WHERE id = :id";
    $db = config::getConnexion();
    try {
        $query = $db->prepare($sql);
        $query->bindValue(":id", $id);
        $query->execute();
        return true; // Retourne true si la suppression a réussi
    } catch (Exception $e) {
        echo $e->getMessage();
        return false; // Retourne false en cas d'erreur
    }
}
 // ✅ Nouvelle méthode pour ajouter un produit
    public static function ajouterProduitt($categorie, $nom, $marque, $materiel, $prix, $stock, $pays)
{
    $sql = "INSERT INTO produit (categorie, nom, marque, materiel, prix, stock, pays)
            VALUES (:cat, :nom, :mar, :mat, :prx, :stc, :pay)";
    $db = config::getConnexion();
    try {
        $query = $db->prepare($sql);
        $query->bindValue(":cat", $categorie);
        $query->bindValue(":nom", $nom);
        $query->bindValue(":mar", $marque);
        $query->bindValue(":mat", $materiel);
        $query->bindValue(":prx", $prix);
        $query->bindValue(":stc", $stock);
        $query->bindValue(":pay", $pays);

        $query->execute();
    } catch (Exception $e) {
        die("Erreur lors de l'ajout du produit : " . $e->getMessage());
    }
}

public static function ajouterProduit(Produit $prod)
{
    $sql = "INSERT INTO produit (categorie, nom, marque, materiel, prix, stock, pays) 
            VALUES (:cat, :nom, :mar, :mat, :prx, :stc, :pay)";
    
    $db = config::getConnexion();
    try {
        $query = $db->prepare($sql);
        $query->bindValue(":cat", $prod->getCategorie());
        $query->bindValue(":nom", $prod->getNom());
        $query->bindValue(":mar", $prod->getMarque());
        $query->bindValue(":mat", $prod->getMateriel());
        $query->bindValue(":prx", $prod->getPrix());
        $query->bindValue(":stc", $prod->getStock());
        $query->bindValue(":pay", $prod->getPays());

        $query->execute();
    } catch (Exception $e) {
        die($e->getMessage());
    }
}

public static function getProduitsFiltres($filters) {
        $sql = "SELECT p.*, c.nom AS cnom FROM produit p
                JOIN categorieProduit c ON p.categorie = c.id
                WHERE 1=1";
        $params = [];
    
        if (!empty($filters['nom'])) {
            $sql .= " AND p.nom LIKE ?";
            $params[] = "%" . $filters['nom'] . "%";
        }
        if (!empty($filters['categorie'])) {
            $sql .= " AND c.id = ?";
            $params[] = $filters['categorie'];
        }
        if (!empty($filters['marque'])) {
            $sql .= " AND p.marque LIKE ?";
            $params[] = "%" . $filters['marque'] . "%";
        }
        if (!empty($filters['pays'])) {
            $sql .= " AND p.pays LIKE ?";
            $params[] = "%" . $filters['pays'] . "%";
        }
    
        if (!empty($filters['order'])) {
            switch ($filters['order']) {
                case 'prix_asc':
                    $sql .= " ORDER BY p.prix ASC";
                    break;
                case 'prix_desc':
                    $sql .= " ORDER BY p.prix DESC";
                    break;
                case 'stock_asc':
                    $sql .= " ORDER BY p.stock ASC";
                    break;
                case 'stock_desc':
                    $sql .= " ORDER BY p.stock DESC";
                    break;
            }
        }
    
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute($params);
            return $query->fetchAll();
        } catch (PDOException $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }
    

}