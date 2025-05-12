<?php 
$active_menu = 'posts'; 
include_once 'C:\xampp\htdocs\lezm\view\admin\partials\sidebar.php'; 
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<script src="/lezm/view/assets/js/script.js" defer></script>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Créer un Post 🌿</title>

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap">

<style>
    /* Global Styles */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Roboto', sans-serif;
    }

    body {
        background: linear-gradient(135deg, #0b1c2c, #1d3557); /* Dark Blue Gradient */
        min-height: 100vh;
        display: flex;
        color: #fff;
        font-size: 24px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .sidebar {
        width: 250px;
        background: #2c3e50; /* Dark sidebar */
        color: #fff;
        position: fixed;
        top: 0;
        left: 0;
        height: 100%;
        padding: 20px;
        border-right: 1px solid #ddd;
        z-index: 1000;
        box-shadow: 4px 0 10px rgba(0, 0, 0, 0.3);
    }

    .content-area {
        margin-left: 250px;
        width: calc(100% - 250px);
        min-height: 100vh;
        display: flex;
        align-items: flex-start;
        justify-content: center;
        padding: 20px;
    }

    .form-container {
        background: #fff;
        border-radius: 15px;
        padding: 50px;
        width: 100%;
        max-width: 1000px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        border: 5px solid #28a745; /* Cadre vert autour du formulaire */
        transition: all 0.3s ease;
    }

    .form-container:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
    }

    /* Header and Form Labels */
    h2 {
        font-size: 4rem;
        font-weight: 700;
        color: #1d3557;
        margin-bottom: 40px;
        text-align: center;
        background: linear-gradient(135deg, #28a745, #006400);
        -webkit-background-clip: text;
        color: transparent;
        text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.4);
    }

    h3 {
        font-size: 2.8rem;
        font-weight: 600;
        color: #1d3557;
        margin-bottom: 20px;
        text-align: center;
    }

    label {
        font-size: 1.8rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 10px;
    }

    /* Input and Textarea */
    input[type="text"], textarea, select {
        font-size: 1.8rem;
        color: #333;
        background: #f7f7f7;
        border: 2px solid #ddd;
        padding: 18px;
        border-radius: 10px;
        width: 100%;
        margin-bottom: 20px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    input[type="text"]:focus, textarea:focus, select:focus {
        border-color: #28a745;
        outline: none;
        box-shadow: 0 0 10px rgba(40, 167, 69, 0.5);
    }

    /* Button Styles */
    .btn {
        width: 100%;
        padding: 18px;
        border: none;
        border-radius: 8px;
        font-size: 1.8rem;
        background: #28a745;
        color: #fff;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .btn:hover {
        background: #218838;
        transform: translateY(-2px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
    }

    .btn-add-comment {
        width: auto;
        padding: 15px 30px;
        background: #007bff;
        font-size: 1.6rem;
        margin-bottom: 20px;
    }

    .btn-add-comment:hover {
        background: #0056b3;
    }

    .btn-remove-comment {
        width: auto;
        padding: 15px 30px;
        background: #dc3545;
        font-size: 1.6rem;
        margin-top: 10px;
    }

    .btn-remove-comment:hover {
        background: #c82333;
    }

    .comment-group {
        background: #f9f9f9;
        padding: 30px;
        border: 1px solid #eee;
        border-radius: 10px;
        margin-bottom: 30px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .button-group {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 30px;
    }

    /* Media Queries for Responsiveness */
    @media (max-width: 1024px) {
        .form-container {
            padding: 40px;
        }
        h2 {
            font-size: 3rem;
        }
        h3 {
            font-size: 2.2rem;
        }
    }

    @media (max-width: 768px) {
        .sidebar {
            width: 200px;
        }
        .content-area {
            margin-left: 200px;
            width: calc(100% - 200px);
        }
    }

    @media (max-width: 480px) {
        body {
            padding: 0;
        }
        .content-area {
            margin-left: 0;
            width: 100%;
            padding: 10px;
        }
        .sidebar {
            display: none;
        }
        .form-container {
            padding: 20px;
            height: auto;
            min-height: 100vh;
        }
        h2 {
            font-size: 2.5rem;
        }
        h3 {
            font-size: 2rem;
        }
    }
</style>
</head>
<body>

<?php include_once 'C:\xampp\htdocs\lezm\view\admin\partials\sidebar.php'; ?>

<div class="content-area">
    <div class="form-container">
        <h2>➕ Créer un Nouveau Post</h2>

        <form id="createPostForm" method="POST" action="/lezm/admin/posts/create">
            <div class="form-group">
                <label for="titre">Titre :</label>
                <input type="text" name="titre" id="titre" required placeholder="Entrer le titre du post">
            </div>

            <div class="form-group">
                <label for="description">Description :</label>
                <textarea name="description" id="description" rows="6" required placeholder="Entrer la description du post"></textarea>
            </div>

            <div class="form-group">
                <label for="statut">Statut :</label>
                <select name="statut" id="statut" required>
                    <option value="">-- Choisir un statut --</option>
                    <option value="actif">Actif</option>
                    <option value="inactif">Inactif</option>
                </select>
            </div>

            <h3>Ajouter des Commentaires (Optionnel)</h3>
            <div id="comments-container">
                <!-- Comments will be added here dynamically -->
            </div>
            <button type="button" class="btn btn-add-comment" onclick="addCommentField()">+ Ajouter un Commentaire</button>

            <div class="button-group">
                <button type="button" class="btn btn-read" onclick="readAloud()">🔊 Lire</button>
                <button type="submit" class="btn">🚀 Créer le Post</button>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript for Dynamic Comments, Validation, and Text-to-Speech -->
<script>
function addCommentField() {
    const container = document.getElementById('comments-container');
    const commentIndex = container.children.length;
    const commentGroup = document.createElement('div');
    commentGroup.className = 'comment-group';
    commentGroup.innerHTML = `
        <div class="form-group">
            <label for="auteur-${commentIndex}">Auteur :</label>
            <input type="text" name="auteur[]" id="auteur-${commentIndex}" placeholder="Entrer le nom de l'auteur">
        </div>
        <div class="form-group">
            <label for="content-${commentIndex}">Contenu :</label>
            <textarea name="content[]" id="content-${commentIndex}" rows="4" placeholder="Entrer le contenu du commentaire"></textarea>
        </div>
        <button type="button" class="btn btn-remove-comment" onclick="this.parentElement.remove()">Supprimer ce Commentaire</button>
    `;
    container.appendChild(commentGroup);
}

document.getElementById('createPostForm').addEventListener('submit', function(e) {
    const titre = document.getElementById('titre').value.trim();
    const description = document.getElementById('description').value.trim();
    const statut = document.getElementById('statut').value.trim();
    if (!titre || !description || !statut) {
        alert('Tous les champs sont obligatoires.');
        e.preventDefault();
    }
});

function readAloud() {
    const text = document.getElementById('titre').value + ' ' + document.getElementById('description').value;
    const utterance = new SpeechSynthesisUtterance(text);
    speechSynthesis.speak(utterance);
}
</script>

</body>
</html>
