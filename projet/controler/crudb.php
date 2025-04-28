<?php
require_once('../../config.php');
$conn = config::getConnexion();

// Fonction pour récupérer l'ID du rôle à partir de son nom
function getRoleIdByName($roleName) {
    global $conn;
    $stmt = $conn->prepare("SELECT id FROM role WHERE name = :name");
    $stmt->execute(['name' => $roleName]);
    $role = $stmt->fetch(PDO::FETCH_ASSOC);
    return $role ? $role['id'] : null;
}

// Vérifie si email ou numéro existe déjà
function isDuplicate($email, $numero) {
    global $conn;
    $stmt = $conn->prepare("SELECT COUNT(*) FROM utilisateurs WHERE email = :email OR numero = :numero");
    $stmt->execute(['email' => $email, 'numero' => $numero]);
    return $stmt->fetchColumn() > 0;
}

// AJOUTER
if (isset($_POST['ajouter'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $numero = $_POST['numero'];
    $mdp = $_POST['mdp'];
    $roleName = $_POST['role'];

    $roleId = getRoleIdByName($roleName);

    if ($roleId === null) {
        echo "<script>alert('Rôle invalide.'); window.history.back();</script>";
        exit;
    }

    if (!isDuplicate($email, $numero)) {
        $stmt = $conn->prepare("INSERT INTO utilisateurs (username, role, email, numero, mdp) 
                                VALUES (:username, :role, :email, :numero, :mdp)");
        $stmt->execute([
            'username' => $username,
            'role' => $roleId,
            'email' => $email,
            'numero' => $numero,
            'mdp' => $mdp
        ]);
        header("Location: utilisateurs.php");
    } else {
        echo "<script>alert('Email ou numéro déjà utilisé.'); window.history.back();</script>";
    }
}

// MODIFIER
if (isset($_POST['modifier'])) {
    $id = $_POST['id_user'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $numero = $_POST['numero'];
    $mdp = $_POST['mdp'];
    $roleName = $_POST['role'];

    $roleId = getRoleIdByName($roleName);

    if ($roleId === null) {
        echo "<script>alert('Rôle invalide.'); window.history.back();</script>";
        exit;
    }

    $stmt = $conn->prepare("UPDATE utilisateurs 
                            SET username = :username, role = :role, email = :email, numero = :numero, mdp = :mdp 
                            WHERE id_user = :id");
    $stmt->execute([
        'id' => $id,
        'username' => $username,
        'role' => $roleId,
        'email' => $email,
        'numero' => $numero,
        'mdp' => $mdp
    ]);
    header("Location: utilisateurs.php");
}

// SUPPRIMER
if (isset($_POST['delete'])) {
    $id = $_POST['id_user'];
    $stmt = $conn->prepare("DELETE FROM utilisateurs WHERE id_user = :id");
    $stmt->execute(['id' => $id]);
    header("Location: utilisateurs.php");
}
?>
