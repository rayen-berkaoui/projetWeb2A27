<?php
require_once('../../config.php');
$conn = config::getConnexion();

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
    $role = $_POST['role'];
    $email = $_POST['email'];
    $numero = $_POST['numero'];
    $mdp = $_POST['mdp'];
    

    if (!isDuplicate($email, $numero)) {
        $stmt = $conn->prepare("INSERT INTO utilisateurs (username, role, email, numero, mdp) 
                                VALUES (:username, :role, :email, :numero, :mdp)");
        $stmt->execute([
            'username' => $username,
            'role' => $role,
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
    $role = $_POST['role'];
    $email = $_POST['email'];
    $numero = $_POST['numero'];
    $mdp = $_POST['mdp'];

    $stmt = $conn->prepare("UPDATE utilisateurs SET username = :username, role = :role, status = :status, email = :email, numero = :numero, mdp = :mdp WHERE id_user = :id");
    $stmt->execute([
        'id' => $id,
        'username' => $username,
        'role' => $role,
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

<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('form-ajouter-modifier');
    if (!form) return;

    form.addEventListener('submit', function (e) {
        const username = document.getElementById('username').value.trim();
        const numero = document.getElementById('numero').value.trim();
        const mdp = document.getElementById('mdp').value.trim();
        const email = document.getElementById('email').value.trim();
        const role = document.getElementById('role').value;

        const emailValid = /^[^@.]+@[^@.]+\.[^@.]+$/.test(email);
        const mdpValid = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{6}$/.test(mdp);
        const numeroValid = /^\d{8}$/.test(numero);

        if (username.length > 15) {
            alert("Le nom d'utilisateur ne doit pas dépasser 15 caractères.");
            e.preventDefault();
            return;
        }

        if (!numeroValid) {
            alert("Le numéro doit contenir exactement 8 chiffres.");
            e.preventDefault();
            return;
        }

        if (!mdpValid) {
            alert("Le mot de passe doit contenir 6 caractères, avec une majuscule, une minuscule et un chiffre.");
            e.preventDefault();
            return;
        }

        if (!emailValid || (email.match(/@/g) || []).length !== 1 || (email.match(/\./g) || []).length !== 1) {
            alert("Email invalide. Il doit contenir exactement un '@' et un seul '.'");
            e.preventDefault();
            return;
        }

        if (!(role === "utilisateur" || role === "organisateur")) {
            alert("Le rôle doit être 'utilisateur' ou 'organisateur'.");
            e.preventDefault();
            return;
        }

    });
});
</script>
