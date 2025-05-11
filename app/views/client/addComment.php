<?php
include_once 'C:\xampp\htdocs\2A27\app\controller\CommentaireC.php';
include_once 'C:\xampp\htdocs\2A27\app\models\Commentaire.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['comment'])) {
    // Debugging: Check if all POST variables are set
    error_log("POST data: " . print_r($_POST, true)); // Logs POST data
    
    $post_id = $_POST['post_id'];
    $content = $_POST['content'];
    $author = $_POST['author'];

    // Check if all fields are filled
    if (!empty($post_id) && !empty($content) && !empty($author)) {
        // Create a new Commentaire object
        $commentaire = new Commentaire($post_id, $content, $author);

        // Create the controller object and call the addCommentaire method
        $commentaireC = new CommentaireC();
        $result = $commentaireC->addCommentaire($commentaire);

        // Debugging: Check result
        error_log("Result: " . $result); // Logs the result of the addCommentaire method

        if ($result) {
            echo "success"; // Return success if the comment was added
        } else {
            echo "error"; // Return error if something went wrong
        }
    } else {
        echo "error"; // Return error if fields are empty
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un Commentaire</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #4CAF50; /* Fond vert */
            margin: 0;
            padding: 0;
            color: #fff; /* Texte en blanc pour contraster avec le fond vert */
        }

        .navbar {
            background-color: #333;
            padding: 10px 20px;
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar img {
            height: 40px;
        }

        .nav-links {
            display: flex;
            gap: 20px;
        }

        .nav-link {
            color: #fff;
            text-decoration: none;
            font-size: 16px;
        }

        .nav-link:hover {
            text-decoration: underline;
        }

        .container {
            width: 80%;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            color: #333; /* Texte sombre à l'intérieur des sections */
        }

        .form-wrapper {
            max-width: 600px;
            margin: 0 auto;
        }

        h2 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            font-size: 16px;
            margin-bottom: 5px;
        }

        input[type="number"],
        input[type="text"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        textarea {
            resize: vertical;
            height: 100px;
        }

        .submit-comment {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 4px;
            font-size: 16px;
        }

        .submit-comment:hover {
            background-color: #0056b3;
        }

        #comments-section {
            margin-top: 30px;
        }

        .comment-card {
            background-color: #f9f9f9;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .comment-card strong {
            font-size: 16px;
            color: #333;
        }

        .comment-card p {
            margin: 10px 0 0;
            font-size: 14px;
            color: #555;
        }

        .comment-card .author {
            color: #007bff;
        }
    </style>
    <script>
        $(document).ready(function(){
            // Soumettre le formulaire de commentaire via AJAX
            $(".submit-comment").click(function(event){
                event.preventDefault(); // Empêcher le formulaire de se soumettre normalement
                var postId = $("#post_id").val();
                var content = $("#content").val();
                var author = $("#author").val();

                // Validation des champs
                var errorMessage = "";

                if(content == "") {
                    errorMessage += "Le contenu du commentaire est requis.\n";
                }
                if(author == "") {
                    errorMessage += "L'auteur est requis.\n";
                }
                if(postId == "") {
                    errorMessage += "L'ID du post est requis.\n";
                }

                if(errorMessage != "") {
                    alert(errorMessage); // Afficher les erreurs si des champs sont vides
                } else {
                    // Si aucune erreur, soumettre le formulaire via AJAX
                    $.ajax({
                        url: "addCommentaire.php", // Même fichier pour traiter la requête AJAX
                        type: "POST",
                        data: {
                            comment: true,
                            post_id: postId,
                            content: content,
                            author: author
                        },
                        success: function(response) {
                            if(response == "success") {
                                // Ajouter le commentaire dynamiquement à la page
                                var commentHtml = "<div class='comment-card'><strong class='author'>" + author + ":</strong><p>" + content + "</p></div>";
                                $("#comments-section").append(commentHtml);
                                // Réinitialiser le formulaire
                                $("#content").val("");
                                $("#author").val("");
                            } else {
                                alert("Erreur: veuillez remplir tous les champs.");
                            }
                        }
                    });
                }
            });
        });
    </script>
</head>
<body>

<div class="navbar">
    <img src="asset/logo.png" alt="Logo">
    <div class="nav-links">
        <a href="addPost.php" class="nav-link">Posts</a>
    </div>
</div>

<div class="container">
    <div class="form-wrapper">
        <h2>Ajouter un Commentaire</h2>

        <!-- Formulaire pour ajouter un commentaire -->
        <form id="comment-form">
            <div class="form-group">
                <label for="post_id">ID du Post</label>
                <input type="number" id="post_id" required>
            </div>
            <div class="form-group">
                <label for="content">Contenu</label>
                <textarea id="content" required></textarea>
            </div>
            <div class="form-group">
                <label for="author">Auteur</label>
                <input type="text" id="author" required>
            </div>
            <button type="button" class="submit-comment">Ajouter le commentaire</button>
        </form>

        <!-- Section pour afficher les commentaires après ajout -->
        <div id="comments-section">
            <!-- Les commentaires seront ajoutés ici dynamiquement -->
        </div>
    </div>
</div>

</body>
</html>
