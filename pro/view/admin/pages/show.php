<?php
$active_menu = 'posts';
include_once 'C:\xampp\htdocs\lezm\view\admin\partials\sidebar.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Post</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(to right, #e0f7fa, #f1f8e9);
            color: #333;
            display: flex;
            min-height: 100vh;
            font-size: 18px;
        }

        .sidebar {
            width: 250px;
            background-color: #2c3e50;
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            padding: 30px 20px;
        }

        .content-area {
            margin-left: 270px;
            padding: 40px 30px;
            flex: 1;
            background-color: rgba(255, 255, 255, 0.8);
            border-top-left-radius: 20px;
            border-bottom-left-radius: 20px;
            box-shadow: -2px 0 10px rgba(0,0,0,0.1);
        }

        .post-container {
            background: white;
            border-radius: 10px;
            padding: 30px;
            max-width: 800px;
            margin: 0 auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2 {
            font-size: 32px;
            color: #2c3e50;
            margin-bottom: 20px;
            text-align: center;
        }

        .post-detail {
            margin-bottom: 15px;
            font-size: 20px;
        }

        .post-detail strong {
            color: #388e3c;
        }

        .comments-section {
            margin-top: 30px;
        }

        .comment {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
        }

        .btn {
            padding: 10px 20px;
            font-size: 1.1rem;
            cursor: pointer;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            transition: background 0.3s ease;
            display: inline-block;
            background-color: #3498db;
            color: white;
            margin-top: 20px;
        }

        .btn:hover {
            background-color: #2e86c1;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
            }

            .content-area {
                margin-left: 220px;
                padding: 20px;
            }

            h2 {
                font-size: 26px;
            }

            .post-container {
                padding: 20px;
            }

            .post-detail {
                font-size: 18px;
            }
        }

        @media (max-width: 480px) {
            .content-area {
                margin-left: 0;
                padding: 10px;
            }

            .sidebar {
                display: none;
            }

            .post-container {
                padding: 15px;
            }

            .post-detail {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>

    <?php include_once 'C:\xampp\htdocs\lezm\view\admin\partials\sidebar.php'; ?>

    <div class="content-area">
        <div class="post-container">
            <h2>Détails du Post</h2>
            <?php if ($post): ?>
                <div class="post-detail"><strong>ID:</strong> <?= htmlspecialchars($post['id']) ?></div>
                <div class="post-detail"><strong>Titre:</strong> <?= htmlspecialchars($post['titre']) ?></div>
                <div class="post-detail"><strong>Description:</strong> <?= htmlspecialchars($post['description']) ?></div>
                <div class="post-detail"><strong>Statut:</strong> <?= htmlspecialchars($post['statut']) ?></div>
                <div class="post-detail"><strong>Créé le:</strong> <?= htmlspecialchars($post['dateCreation']) ?></div>
                <?php if (!empty($post['pdf_path'])): ?>
                    <div class="post-detail"><strong>PDF:</strong> <a href="<?= htmlspecialchars($post['pdf_path']) ?>" download>Télécharger</a></div>
                <?php endif; ?>

                <div class="comments-section">
                    <h3>Commentaires</h3>
                    <?php if (!empty($comments)): ?>
                        <?php foreach ($comments as $comment): ?>
                            <div class="comment">
                                <p><strong>Auteur:</strong> <?= htmlspecialchars($comment['auteur']) ?></p>
                                <p><strong>Contenu:</strong> <?= htmlspecialchars($comment['content']) ?></p>
                                <p><strong>Date:</strong> <?= htmlspecialchars($comment['date_creation']) ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Aucun commentaire.</p>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <p>Post non trouvé.</p>
            <?php endif; ?>
            <a href="/lezm/admin/posts" class="btn">Retour à la Liste</a>
        </div>
    </div>

</body>
</html>