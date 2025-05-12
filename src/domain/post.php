<?php
class Post {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function all() {
        $stmt = $this->pdo->query("SELECT * FROM post ORDER BY dateCreation DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM post WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($titre, $description, $statut) {
        $stmt = $this->pdo->prepare("INSERT INTO post (titre, description, statut, dateCreation) VALUES (?, ?, ?, NOW())");
        return $stmt->execute([$titre, $description, $statut]);
    }

    public function update($id, $titre, $description, $statut) {
        $stmt = $this->pdo->prepare("UPDATE post SET titre = ?, description = ?, statut = ? WHERE id = ?");
        return $stmt->execute([$titre, $description, $statut, $id]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM post WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
