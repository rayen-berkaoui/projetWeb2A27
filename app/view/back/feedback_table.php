<!-- Include feedback CSS -->
<link href="/GreenMind/app/view/back/2A27/view/assets/css/feedback.css" rel="stylesheet">

<div class="feedback-container">
    <div class="feedback-header">
        <h2 class="feedback-title">Avis Clients</h2>
        <div class="feedback-actions">
            <a href="#" class="feedback-btn">Exporter</a>
            <a href="#" class="feedback-btn">Nouveau</a>
        </div>
    </div>

    <table class="feedback-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Commentaire</th>
                <th>Note</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($feedbacks)): ?>
                <tr>
                    <td colspan="6" style="text-align: center;">Aucun avis trouvé</td>
                </tr>
            <?php else: ?>
                <?php foreach ($feedbacks as $feedbacks): ?>
                    <tr>
                        <td data-label="ID"><?= htmlspecialchars($feedbacks['id']) ?></td>
                        <td data-label="Nom"><?= htmlspecialchars($feedbacks['nom']) ?></td>
                        <td data-label="Prénom"><?= htmlspecialchars($feedbacks['prenom']) ?></td>
                        <td data-label="Commentaire">
                            <?php 
                            $comment = htmlspecialchars($feedbacks['commentaire']);
                            echo (strlen($comment) > 50) ? substr($comment, 0, 50) . '...' : $comment;
                            ?>
                        </td>
                        <td data-label="Note">
                            <div class="rating">
                                <?php
                                $rating = (int)$feedbacks['note'];
                                for ($i = 1; $i <= 5; $i++) {
                                    if ($i <= $rating) {
                                        echo '<span class="star-filled">★</span>';
                                    } else {
                                        echo '<span>☆</span>';
                                    }
                                }
                                ?>
                            </div>
                        </td>
                        <td data-label="Actions">
                            <div class="actions">
                                <a href="#" class="action-btn view-btn">Voir</a>
                                <a href="#" class="action-btn edit-btn">Éditer</a>
                                <a href="#" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet avis?');" class="action-btn delete-btn">Supprimer</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    
    <div class="feedbacks-pagination">
        <button>1</button>
        <button class="active">2</button>
        <button>3</button>
        <button>Suivant</button>
    </div>
</div>
