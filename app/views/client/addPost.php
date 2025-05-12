<?php
include_once 'app/controller/PostC.php';

$postC = new PostC();
$formValues = ['titre' => '', 'description' => '', 'dateCreation' => '', 'statut' => 'actif'];
$success = ''; // Initialisation de la variable success
$error = '';   // Initialisation de la variable error

// Traitement du formulaire
if (isset($_POST['action']) && $_POST['action'] == 'add') {
    $formValues = [
        'titre' => isset($_POST["titre"]) ? $_POST["titre"] : '',
        'description' => isset($_POST["description"]) ? $_POST["description"] : '',
        'dateCreation' => date('Y-m-d H:i:s'),
        'statut' => isset($_POST["statut"]) ? $_POST["statut"] : 'actif'
    ];

    try {
        // Création d'un objet Posts et ajout à la base de données
        $post = new Posts(
            $formValues['titre'],
            $formValues['description'],
            $formValues['dateCreation'],
            $formValues['statut']
        );
        $postC->ajouterPost($post);
        $success = "Post ajouté avec succès !"; // Message de succès
        $formValues = ['titre' => '', 'description' => '', 'dateCreation' => '', 'statut' => 'actif']; // Réinitialisation du formulaire
    } catch (Exception $e) {
        // En cas d'erreur
        $error = "Erreur lors de l'ajout: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un Post</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #1f2a44; /* Bleu foncé */
            color: #fff;
            padding: 0;
            margin: 0;
        }

        header {
            background: linear-gradient(90deg, #0077cc, #005fa3);
            padding: 40px 80px;
            color: white;
            text-align: center;
            box-shadow: 0 6px 15px rgba(0,0,0,0.3);
            border-bottom: 5px solid #003b5c;
            transform: perspective(150px) rotateX(5deg);
        }

        header h1 {
            font-size: 3em;
            font-weight: 600;
        }

        .container {
            max-width: 80%;
            margin: 50px auto;
            padding: 50px;
            background: #2c3e50;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease-in-out;
            color: white;
        }

        .container:hover {
            transform: scale(1.03);
            box-shadow: 0 18px 45px rgba(0, 0, 0, 0.3);
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #e0e0e0;
            font-size: 2em;
        }

        .form-group {
            margin-bottom: 25px;
        }

        label {
            font-weight: 600;
            display: block;
            margin-bottom: 12px;
            font-size: 1.2em;
        }

        input[type="text"],
        textarea,
        select {
            width: 100%;
            padding: 18px;
            border: none;
            border-radius: 12px;
            background: #34495e;
            color: #fff;
            font-size: 1.1em;
            transition: background-color 0.3s ease-in-out, border-color 0.3s ease;
        }

        input[type="text"]:focus,
        textarea:focus,
        select:focus {
            background: #2980b9;
            outline: none;
            border-color: #0077cc;
        }

        textarea {
            resize: vertical;
            min-height: 150px;
        }

        input[type="submit"] {
            width: 100%;
            padding: 16px;
            background: #0077cc;
            color: white;
            font-size: 1.2em;
            font-weight: bold;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: background 0.3s ease-in-out, transform 0.2s ease;
        }

        input[type="submit"]:hover {
            background: #005fa3;
            transform: scale(1.02);
        }

        .message {
            padding: 18px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .success {
            background-color: #e6f4ea;
            color: #256029;
            border-left: 5px solid #4caf50;
        }

        .error {
            background-color: #ffe5e5;
            color: #c62828;
            border-left: 5px solid #f44336;
        }

        .js-error {
            color: #c62828;
            font-size: 1em;
            margin-top: 6px;
        }

        @media (max-width: 768px) {
            .container {
                max-width: 95%;
                padding: 25px;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Interface d'Ajout de Post</h1>
    </header>

    <div class="container">
        <h2>Ajouter un Nouveau Post</h2>

        <!-- Affichage du message de succès ou d'erreur -->
        <?php if ($success): ?>
            <div class="message success"><?= $success ?></div>
        <?php elseif ($error): ?>
            <div class="message error"><?= $error ?></div>
        <?php endif; ?>

        <form id="postForm" action="/lezm/forum" method="post" novalidate>
            <input type="hidden" name="action" value="add">

            <div class="form-group">
                <label for="titre">Titre :</label>
                <input type="text" id="titre" name="titre" value="<?= htmlspecialchars($formValues['titre']) ?>" required>
                <div id="titre-error" class="js-error"></div>
            </div>

            <div class="form-group">
                <label for="description">Description :</label>
                <textarea id="description" name="description" required><?= htmlspecialchars($formValues['description']) ?></textarea>
                <div id="description-error" class="js-error"></div>
            </div>

            <div class="form-group">
                <label for="statut">Statut :</label>
                <select id="statut" name="statut">
                    <option value="actif" <?= $formValues['statut'] === 'actif' ? 'selected' : '' ?>>Actif</option>
                    <option value="inactif" <?= $formValues['statut'] === 'inactif' ? 'selected' : '' ?>>Inactif</option>
                </select>
            </div>

            <input type="submit" value="Ajouter le post">
        </form>
    </div>

    <script>
        document.getElementById('postForm').addEventListener('submit', function(event) {
            let isValid = true;
            const titre = document.getElementById('titre');
            const titreError = document.getElementById('titre-error');
            const description = document.getElementById('description');
            const descriptionError = document.getElementById('description-error');

            titreError.textContent = "";
            descriptionError.textContent = "";

            if (titre.value.trim().length < 3) {
                titreError.textContent = "Le titre doit contenir au moins 3 caractères.";
                isValid = false;
            }

            if (description.value.trim().length < 10) {
                descriptionError.textContent = "La description doit contenir au moins 10 caractères.";
                isValid = false;
            }

            if (!isValid) {
                event.preventDefault(); // Empêche l'envoi du formulaire
            }
        });
    </script>
</body>
</html>