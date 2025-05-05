<?php
class User {
    private $conn;

    public function __construct() {
        $this->conn = new PDO("mysql:host=localhost;dbname=bd_avis", "root", "");
    }

    public function afficherAvis() {
        $sql = "SELECT * FROM avis ORDER BY date_creation DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function ajouterAvis($user_id, $contenu, $note) {
        $sql = "INSERT INTO avis (user_id, contenu, note, date_creation) VALUES (?, ?, ?, NOW())";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$user_id, $contenu, $note]);
    }

    public function supprimerAvis($id) {
        $sql = "DELETE FROM avis WHERE avis_id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id]);
    }
}
?>
