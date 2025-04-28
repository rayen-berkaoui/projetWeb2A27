<?php

class User {
    private $id_user;  // Adjusted to match the database field name
    private $username;
    private $email;
    private $mdp;
    private $role;
    private $numero;
    private $login_count;

    // Constructor
    public function __construct($username, $email, $mdp, $role, $numero, $id_user = null, $login_count = 0) {
        $this->username = $username;
        $this->email = $email;
        $this->mdp = $mdp;
        $this->role = $role;
        $this->numero = $numero;
        $this->id_user = $id_user;
        $this->login_count = $login_count;
    }

    // Getter methods
    public function getId() {
        return $this->id_user;  // Use id_user instead of id
    }

    public function getUsername() {
        return $this->username;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getRole() {
        return $this->role;
    }

    public function getNumero() {
        return $this->numero;
    }

    public function getLoginCount() {
        return $this->login_count;
    }

    // Setter methods if needed
    public function setLoginCount($login_count) {
        $this->login_count = $login_count;
    }

    public function setId($id_user) {
        $this->id_user = $id_user;
    }
}

?>


    // Method to save user in the database
    public function save($pdo) {
        if ($this->id_user) {
            // Update user if the id exists
            $query = "UPDATE utilisateurs SET username = ?, role = ?, status = ?, email = ?, mdp = ?, numero = ? WHERE id_user = ?";
            $stmt = $pdo->prepare($query);
            return $stmt->execute([$this->username, $this->role, $this->status, $this->email, $this->mdp, $this->numero, $this->id_user]);
        } else {
            // Insert new user if no id exists
            $query = "INSERT INTO utilisateurs (username, role, status, email, mdp, numero) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($query);
            return $stmt->execute([$this->username, $this->role, $this->status, $this->email, $this->mdp, $this->numero]);
        }
    }

    // Method to find user by email
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

    // Method to update the password
    public static function updatePassword($email, $newPassword, $pdo) {
        $query = "UPDATE utilisateurs SET mdp = ? WHERE email = ?";
        $stmt = $pdo->prepare($query);
        return $stmt->execute([$newPassword, $email]);
    }
}
?>
