<?php
class Database {
    private $host = 'localhost';  // Assure-toi que c'est correct
    private $db_name = 'avis_db'; // Met à jour avec le nom correct de ta base de données
    private $username = 'root';   // Si tu utilises 'root' comme utilisateur
    private $password = '';       // Si tu n'as pas de mot de passe (par défaut pour XAMPP)
    private $conn;

    public function __construct() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
    }

    public function getConnection() {
        return $this->conn;
    }

    public function prepare($query) {
        return $this->conn->prepare($query);
    }

    public function query($query) {
        return $this->conn->query($query);
    }
}
?>
