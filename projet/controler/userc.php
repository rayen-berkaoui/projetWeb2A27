<?php
require_once('C:\xampp1\htdocs\web\projet\model\user.php');

class UserController {
    private $pdo;

    public function __construct() {
        try {
            $this->pdo = new PDO("mysql:host=localhost;dbname=projetweb2a", "root", "");
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->createDefaultOrganisateurRole();
        } catch (PDOException $e) {
            die("❌ Échec de connexion BDD : " . $e->getMessage());
        }
    }

    private function createDefaultOrganisateurRole() {
        try {
            $stmt = $this->pdo->prepare("SELECT id FROM role WHERE name = 'organisateur'");
            $stmt->execute();
            if (!$stmt->fetch()) {
                $insert = $this->pdo->prepare("INSERT INTO role (name) VALUES ('organisateur')");
                $insert->execute();
            }
        } catch (PDOException $e) {
            echo "❌ Erreur création rôle : " . $e->getMessage();
        }
    }

    public function addUser(User $user) {
        try {
            $stmtRole = $this->pdo->prepare("SELECT id FROM role WHERE name = 'organisateur'");
            $stmtRole->execute();
            $roleRow = $stmtRole->fetch(PDO::FETCH_ASSOC);

            if (!$roleRow) throw new Exception("Rôle 'organisateur' manquant.");

            $roleId = $roleRow['id'];

            $stmt = $this->pdo->prepare("
                INSERT INTO utilisateurs (username, email, numero, mdp, role)
                VALUES (?, ?, ?, ?, ?)
            ");

            return $stmt->execute([
                $user->getUsername(),
                $user->getEmail(),
                $user->getNumero(),
                $user->getMdp(),  // mot de passe en clair
                $roleId
            ]);
        } catch (Exception $e) {
            echo "❌ Erreur addUser: " . $e->getMessage();
            return false;
        }
    }

    public function verifyCredentials($username, $password) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT u.*, r.name AS nom_role 
                FROM utilisateurs u
                JOIN role r ON u.role = r.id
                WHERE u.username = ? OR u.email = ?
            ");
            $stmt->execute([$username, $username]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row && $row['mdp'] === $password) { // mot de passe en clair
                $user = new User(
                    $row['username'],
                    $row['nom_role'],
                    $row['email'],
                    $row['mdp'],
                    $row['numero']
                );
                $user->setIdUser($row['id_user']);
                return $user;
            }
        } catch (PDOException $e) {
            echo "❌ Login error: " . $e->getMessage();
        }
        return null;
    }

    public function getUserByEmail($email) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT u.*, r.name AS nom_role 
                FROM utilisateurs u
                JOIN role r ON u.role = r.id
                WHERE u.email = ?
            ");
            $stmt->execute([$email]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                return new User(
                    $row['username'],
                    $row['nom_role'],
                    $row['email'],
                    $row['mdp'],
                    $row['numero']
                );
            }
        } catch (PDOException $e) {
            echo "❌ getUserByEmail error: " . $e->getMessage();
        }
        return null;
    }

    public function updatePassword($email, $newPassword) {
        try {
            $stmt = $this->pdo->prepare("UPDATE utilisateurs SET mdp = ? WHERE email = ?");
            return $stmt->execute([$newPassword, $email]);  // mot de passe en clair
        } catch (PDOException $e) {
            echo "❌ updatePassword error: " . $e->getMessage();
            return false;
        }
    }

    public function updateUser($oldUsername, $newUsername, $email, $numero) {
        try {
            $stmt = $this->pdo->prepare("
                UPDATE utilisateurs
                SET username = ?, email = ?, numero = ?
                WHERE username = ?
            ");
            return $stmt->execute([$newUsername, $email, $numero, $oldUsername]);
        } catch (Exception $e) {
            echo "❌ Erreur updateUser: " . $e->getMessage();
            return false;
        }
    }
}
?>