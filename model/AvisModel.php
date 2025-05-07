<?php
require_once __DIR__ . '/../config/database.php';

class AvisModel {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function getAvis($criteria = null) {
        $sql = "SELECT * FROM avis";
        
        if ($criteria) {
            switch ($criteria) {
                case 'date_desc':
                    $sql .= " ORDER BY date_creation DESC";
                    break;
                case 'date_asc':
                    $sql .= " ORDER BY date_creation ASC";
                    break;
                case 'note_desc':
                    $sql .= " ORDER BY note DESC";
                    break;
                case 'note_asc':
                    $sql .= " ORDER BY note ASC";
                    break;
            }
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function toggleVisibility($id, $state) {
        $stmt = $this->pdo->prepare("UPDATE avis SET is_visible = ? WHERE avis_id = ?");
        return $stmt->execute([(bool)$state, $id]);
    }

    public function updateAvis($id, $data) {
        $stmt = $this->pdo->prepare("
            UPDATE avis 
            SET nom = :nom, 
                prenom = :prenom, 
                email = :email, 
                contenu = :contenu, 
                note = :note
            WHERE avis_id = :id
        ");
        
        return $stmt->execute([
            ':id' => $id,
            ':nom' => $data['nom'],
            ':prenom' => $data['prenom'],
            ':email' => $data['email'],
            ':contenu' => $data['contenu'],
            ':note' => $data['note']
        ]);
    }

    public function deleteAvis($id) {
        $stmt = $this->pdo->prepare("DELETE FROM avis WHERE avis_id = ?");
        return $stmt->execute([$id]);
    }

    public function signalAvis($id) {
        $stmt = $this->pdo->prepare("
            UPDATE avis 
            SET signale = COALESCE(signale, 0) + 1 
            WHERE avis_id = ?
        ");
        return $stmt->execute([$id]);
    }
}