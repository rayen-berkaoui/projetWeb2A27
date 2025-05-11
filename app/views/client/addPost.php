<?php
include_once 'app/controller/PostC.php';

$postC = new PostC();
$formValues = ['titre' => '', 'description' => '', 'dateCreation' => '', 'statut' => 'actif'];
$success = $error = '';

if (isset($_POST['action']) && $_POST['action'] == 'add') {
    $formValues = [
        'titre' => $_POST["titre"] ?? '',
        'description' => $_POST["description"] ?? '',
        'dateCreation' => date('Y-m-d H:i:s'),
        'statut' => $_POST["statut"] ?? 'actif'
    ];

    try {
        $post = new Posts(
            $formValues['titre'],
            $formValues['description'],
            $formValues['dateCreation'],
            $formValues['statut']
        );
        $postC->ajouterPost($post);
        $success = "Post ajouté avec succès !";
        $formValues = ['titre' => '', 'description' => '', 'dateCreation' => '', 'statut' => 'actif'];
    } catch (Exception $e) {
        $error = "Erreur lors de l'ajout: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    
    <title>Ajouter un Post</title>
    <style>
    body {
        margin: 0;
            padding: 0;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #e3f2fd, #ffffff);
            min-height: 100vh;
        }

        nav {
            background: #003366;
            padding: 15px 30px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.3);
        }

        nav ul {
            margin: 0;
            padding: 0;
            list-style: none;
            display: flex;
            justify-content: space-around;
        }

        nav ul li a {
            color: #fff;
            text-decoration: none;
            font-weight: bold;
            font-size: 1.1em;
        }

        .container {
            max-width: 1400px;
            margin: 40px auto;
            padding: 40px;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
            transform: perspective(1000px) rotateX(0deg);
            transition: transform 0.5s ease;
        }

        .container:hover {
            transform: perspective(1000px) rotateX(2deg);
        }

        h2 {
            text-align: center;
            font-size: 2em;
            color: #003366;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        label {
            font-weight: 600;
            font-size: 1.1em;
            display: block;
            margin-bottom: 10px;
        }

        input[type="text"],
        input[type="number"],
        textarea,
        select {
            width: 100%;
            padding: 15px;
            border: 2px solid #003366;
            border-radius: 12px;
            font-size: 1em;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);
        }

        button {
            padding: 15px 30px;
            background: #003366;
            color: white;
            font-weight: bold;
            border: none;
            border-radius: 10px;
            font-size: 1.1em;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background: #002244;
        }

        button.read-btn {
            background: #003366;
            margin-right: 20px;
        }

        button.read-btn:hover {
            background: #002244;
        }

        button.read-btn.speaking {
            background: #256029;
        }

        button.read-btn.speaking:hover {
            background: #1b4a20;
        }

        .error, .success {
            margin: 20px 0;
            padding: 15px;
            border-radius: 8px;
            font-weight: bold;
        }

        .error {
            background-color: #ffcdd2;
            color: #b71c1c;
        }

        .success {
            background-color: #c8e6c9;
            color: #256029;
        }

        .search-result {
            background-color: #f3f3f3;
            padding: 25px;
            border-radius: 12px;
            margin-top: 30px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }

            nav ul {
                flex-direction: column;
                gap: 10px;
            }

            button.read-btn {
                margin-right: 10px;
            }
        }
</style>

</head>
<body>
    <h1>Ajouter un Post</h1>

    <?php if ($success): ?>
        <p style="color: green;"><?= $success ?></p>
    <?php elseif ($error): ?>
        <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>

    <form action="/2A27/forum" method="post">
        <input type="hidden" name="action" value="add">
        <label for="titre">Titre :</label><br>
        <input type="text" id="titre" name="titre" value="<?= htmlspecialchars($formValues['titre']) ?>" required><br><br>

        <label for="description">Description :</label><br>
        <textarea id="description" name="description" required><?= htmlspecialchars($formValues['description']) ?></textarea><br><br>

        <label for="statut">Statut :</label><br>
        <select id="statut" name="statut">
            <option value="actif" <?= $formValues['statut'] === 'actif' ? 'selected' : '' ?>>Actif</option>
            <option value="inactif" <?= $formValues['statut'] === 'inactif' ? 'selected' : '' ?>>Inactif</option>
        </select><br><br>

        <input type="submit" value="Ajouter le post">
    </form>
</body>
</html>
