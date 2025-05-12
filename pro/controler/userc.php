<?php
require_once('C:\xampp\htdocs\lezm\pro\model\user.php');

class UserController {
    private $pdo;
    private $openai_api_key = "";

    public function __construct() {
        try {
            $this->pdo = new PDO("mysql:host=localhost;dbname=db_html", "root", "");
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

            // Insertion sans hachage du mot de passe
            $stmt = $this->pdo->prepare("
                INSERT INTO utilisateurs (username, email, numero, mdp, role)
                VALUES (?, ?, ?, ?, ?)
            ");

            return $stmt->execute([
                $user->getUsername(),
                $user->getEmail(),
                $user->getNumero(),
                $user->getMdp(), // Mot de passe en clair
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

            if ($row && $row['mdp'] === $password) { // Comparaison en clair
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

    public function generatePassword() {
        return $this->callOpenAI("Génère un mot de passe sécurisé.");
    }

    public function getUserByUsername($username) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM utilisateurs WHERE username = ? OR email = ?");
            $stmt->execute([$username, $username]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $user = new User(
                    $row['username'],
                    $row['nom_role'], // Assurez-vous que la colonne existe ou modifiez selon vos colonnes
                    $row['email'],
                    $row['mdp'],
                    $row['numero']
                );
                $user->setIdUser($row['id_user']);
                return $user;
            } else {
                return null; // Aucun utilisateur trouvé
            }
        } catch (PDOException $e) {
            echo "❌ Erreur lors de la récupération de l'utilisateur : " . $e->getMessage();
            return null;
        }
    }

    // Alternative: Ajouter la méthode getUserByEmail() si tu veux la garder séparée
    public function getUserByEmail($email) {
        return $this->getUserByUsername($email); // Reuse the existing method
    }

    private function callOpenAI($prompt) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://api.openai.com/v1/completions");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            'model' => 'text-davinci-003',
            'prompt' => $prompt,
            'max_tokens' => 16,
        ]));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer " . $this->openai_api_key
        ]);

        $response = curl_exec($ch);
        $data = json_decode($response, true);
        return $data['choices'][0]['text'];
    }
}
?>
