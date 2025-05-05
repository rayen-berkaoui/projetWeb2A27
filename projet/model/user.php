<?php
class User {
    private $id_user;
    private $username;
    private $role;
    private $status;
    private $email;
    private $mdp;
    private $numero;

    // Constructor
    public function __construct($username = "", $role = "", $status = "actif", $email = "", $mdp = "", $numero = "00000000") {
        $this->username = $username;
        $this->role = $role;
        $this->status = $status;
        $this->email = $email;
        $this->mdp = $mdp;
        $this->numero = $numero;
    }

    // Getters
    public function getIdUser() {
        return $this->id_user;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getRole() {
        return $this->role;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getMdp() {
        return $this->mdp;
    }

    public function getNumero() {
        return $this->numero;
    }

    // Setters
    public function setIdUser($id_user) {
        $this->id_user = $id_user;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function setRole($role) {
        $this->role = $role;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setMdp($mdp) {
        $this->mdp = $mdp;
    }

    public function setNumero($numero) {
        $this->numero = $numero;
    }

    // Save user to database
    public function save($pdo) {
        if ($this->id_user) {
            $query = "UPDATE utilisateurs SET username = ?, role = ?, status = ?, email = ?, mdp = ?, numero = ? WHERE id_user = ?";
            $stmt = $pdo->prepare($query);
            return $stmt->execute([$this->username, $this->role, $this->status, $this->email, $this->mdp, $this->numero, $this->id_user]);
        } else {
            $query = "INSERT INTO utilisateurs (username, role, status, email, mdp, numero) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($query);
            return $stmt->execute([$this->username, $this->role, $this->status, $this->email, $this->mdp, $this->numero]);
        }
    }

    // Find user by email
    public static function getByEmail($email, $pdo) {
        $query = "SELECT * FROM utilisateurs WHERE email = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $user = new User($row['username'], $row['role'], $row['status'], $row['email'], $row['mdp'], $row['numero']);
            $user->setIdUser($row['id_user']);
            return $user;
        }
        return null;
    }

    // Update password
    public static function updatePassword($email, $newPassword, $pdo) {
        $query = "UPDATE utilisateurs SET mdp = ? WHERE email = ?";
        $stmt = $pdo->prepare($query);
        return $stmt->execute([$newPassword, $email]);
    }
}
?>
