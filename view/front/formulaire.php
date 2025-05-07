<?php
// Connexion à la BDD
try {
    $pdo = new PDO("mysql:host=localhost;dbname=bd_avis;charset=utf8", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Récupération d'un avis spécifique si avis_id est passé en GET
$selectedAvis = null;
if (isset($_GET['avis_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM avis WHERE avis_id = :avis_id");
    $stmt->execute([':avis_id' => (int)$_GET['avis_id']]);
    $selectedAvis = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Récupération des 5 derniers avis publics
$stmt = $pdo->query("SELECT * FROM avis WHERE is_visible = 1 ORDER BY date_creation DESC LIMIT 5");
$avisPublics = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ajouter / Afficher un Avis</title>
  <link rel="stylesheet" href="../assets/css/formulaire.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container">
  <!-- Formulaire -->
  <div class="form-container">
    <h2><?= $selectedAvis ? "Détails de l'avis #{$selectedAvis['avis_id']}" : "Laissez votre avis" ?></h2>
    <form id="avisForm" method="POST">
      <input type="hidden" name="action" value="ajouter">
      <?php if ($selectedAvis): ?>
        <input type="hidden" name="avis_id" value="<?= htmlspecialchars($selectedAvis['avis_id']) ?>">
      <?php endif; ?>

      <div class="form-group">
        <label for="nom">Nom :</label>
        <input
          type="text" name="nom" id="nom"
          value="<?= $selectedAvis ? htmlspecialchars($selectedAvis['nom']) : '' ?>"
          <?= $selectedAvis ? 'readonly' : '' ?>>
        <span class="error-message" id="nom_error"></span>
      </div>

      <div class="form-group">
        <label for="prenom">Prénom :</label>
        <input
          type="text" name="prenom" id="prenom"
          value="<?= $selectedAvis ? htmlspecialchars($selectedAvis['prenom']) : '' ?>"
          <?= $selectedAvis ? 'readonly' : '' ?>>
        <span class="error-message" id="prenom_error"></span>
      </div>

      <div class="form-group">
        <label for="email">Email :</label>
        <input
          type="email" name="email" id="email"
          value="<?= $selectedAvis ? htmlspecialchars($selectedAvis['email']) : '' ?>"
          <?= $selectedAvis ? 'readonly' : '' ?>>
        <span class="error-message" id="email_error"></span>
      </div>

      <div class="form-group">
        <label for="contenu">Votre avis :</label>
        <textarea
          name="contenu" id="contenu"
          <?= $selectedAvis ? 'readonly' : '' ?>><?= $selectedAvis ? htmlspecialchars($selectedAvis['contenu']) : '' ?></textarea>
        <span class="error-message" id="contenu_error"></span>
      </div>

      <div class="form-group">
        <label for="note">Note (1 à 5) :</label>
        <input
          type="number" name="note" id="note"
          value="<?= $selectedAvis ? htmlspecialchars($selectedAvis['note']) : '' ?>"
          <?= $selectedAvis ? 'readonly' : '' ?>>
        <span class="error-message" id="note_error"></span>
      </div>

      <button type="submit" class="submit-btn">
        <?= $selectedAvis ? 'Fermer' : 'Envoyer' ?>
      </button>
    </form>
    <div id="message"></div>
  </div>

  <!-- Section des avis publics -->
  <div class="avis-section">
    <h2>Ce que nos utilisateurs disent</h2>
    <div class="avis-grid">
      <?php foreach ($avisPublics as $a): ?>
        <div class="avis-card" data-avis-id="<?= $a['avis_id'] ?>">
          <!-- Contenu de l'avis -->
          <div class="avis-content">
            <div class="quote">“</div>
            <p class="avis-text"><?= htmlspecialchars($a['contenu']) ?></p>
            <div class="avis-author"><?= htmlspecialchars($a['nom']) ?> <?= htmlspecialchars($a['prenom']) ?> - Note : <?= $a['note'] ?>/5</div>
          </div>

          <!-- Commentaires existants -->
          <div class="existing-comments">
            <?php
            $fetchCom = $pdo->prepare("SELECT * FROM commentaires WHERE avis_id = :avis_id AND is_visible = 1 ORDER BY date_creation DESC");
            $fetchCom->execute([':avis_id' => $a['avis_id']]);
            $commentaires = $fetchCom->fetchAll(PDO::FETCH_ASSOC);
            foreach ($commentaires as $c):
            ?>
              <div class="comment" data-comment-id="<?= $c['commentaire_id'] ?>">
                <strong><?= htmlspecialchars($c['nom']) ?> <?= htmlspecialchars($c['prenom']) ?> :</strong>
                <span><?= htmlspecialchars($c['contenu']) ?></span>
                <div class="comment-actions">
                  <button class="like-btn" data-comment-id="<?= $c['commentaire_id'] ?>">
                    👍 Like (<?= $c['likes'] ?>)
                  </button>
                  <button class="dislike-btn" data-comment-id="<?= $c['commentaire_id'] ?>">
                    👎 Dislike (<?= $c['dislikes'] ?>)
                  </button>
                  <button class="signaler-btn" data-comment-id="<?= $c['commentaire_id'] ?>">
                    Signaler (<?= $c['signale'] ?>)
                  </button>
                </div>
              </div>
            <?php endforeach; ?>
          </div>

          <!-- Formulaire de réponse -->
          <button class="reply-btn">Répondre</button>
          <div class="reply-form" style="display:none;">
            <div class="form-group">
              <label for="reply-nom">Nom :</label>
              <input type="text" class="reply-nom" placeholder="Votre nom">
              <span class="error-message reply-nom-error"></span>
            </div>
            <div class="form-group">
              <label for="reply-prenom">Prénom :</label>
              <input type="text" class="reply-prenom" placeholder="Votre prénom">
              <span class="error-message reply-prenom-error"></span>
            </div>
            <div class="form-group">
              <label for="reply-content">Votre réponse :</label>
              <textarea class="reply-content" placeholder="Votre réponse…"></textarea>
              <span class="error-message reply-content-error"></span>
            </div>
            <button class="submit-reply">Envoyer</button>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<!-- Scripts JavaScript -->
<?php if (!$selectedAvis): ?>
<script>
$(function() {
  $('#avisForm').on('submit', function(e) {
    e.preventDefault();

    // Réinitialiser les messages d'erreur
    $('.error-message').text('');

    // Récupérer les valeurs
    const nom = $('#nom').val().trim();
    const prenom = $('#prenom').val().trim();
    const email = $('#email').val().trim();
    const contenu = $('#contenu').val().trim();
    const note = parseInt($('#note').val());

    // Validation
    let isValid = true;

    if (!nom || nom.length < 2) {
      $('#nom_error').text('Le nom doit contenir au moins 2 caractères.');
      isValid = false;
    }
    if (!prenom || prenom.length < 2) {
      $('#prenom_error').text('Le prénom doit contenir au moins 2 caractères.');
      isValid = false;
    }
    if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
      $('#email_error').text('Veuillez entrer un email valide.');
      isValid = false;
    }
    if (!contenu || contenu.length < 5) {
      $('#contenu_error').text('L\'avis doit contenir au moins 5 caractères.');
      isValid = false;
    }
    if (isNaN(note) || note < 1 || note > 5) {
      $('#note_error').text('La note doit être comprise entre 1 et 5.');
      isValid = false;
    }

    if (!isValid) return;

    // Envoyer la requête AJAX
    $.post('../../controller/crudb.php', $(this).serialize(), function(resp) {
      try {
        const response = typeof resp === 'string' ? JSON.parse(resp) : resp;
        if (response.success) {
          $('#message')
            .text(response.message)
            .addClass('success')
            .show();
          $('#avisForm')[0].reset();
          setTimeout(() => $('#message').fadeOut(), 3000);
        } else {
          throw new Error(response.message);
        }
      } catch (e) {
        console.error('Erreur lors de l\'ajout de l\'avis :', e.message);
        $('#message')
          .text('Erreur : ' + e.message)
          .addClass('error')
          .show();
        setTimeout(() => $('#message').fadeOut(), 3000);
      }
    }).fail(function(xhr) {
      console.error('Erreur réseau (ajout avis) :', xhr.responseText);
      $('#message')
        .text('Erreur réseau : ' + (xhr.responseText || 'Erreur serveur'))
        .addClass('error')
        .show();
      setTimeout(() => $('#message').fadeOut(), 3000);
    });
  });
});
</script>
<?php endif; ?>

<?php if ($selectedAvis): ?>
<script>
document.querySelector('.submit-btn').addEventListener('click', function(e) {
  e.preventDefault();
  window.location.href = 'formulaire.php';
});
</script>
<?php endif; ?>

<script>
$(function() {
  // Ouvre/ferme le formulaire de réponse
  $('.reply-btn').on('click', function() {
    $(this).siblings('.reply-form').slideToggle(200);
  });

  // Soumet la réponse
  $('.submit-reply').on('click', function() {
    const card = $(this).closest('.avis-card');
    const avisId = card.data('avis-id');
    const nom = card.find('.reply-nom').val().trim();
    const prenom = card.find('.reply-prenom').val().trim();
    const contenu = card.find('.reply-content').val().trim();

    // Validation
    let isValid = true;
    card.find('.reply-nom-error').text('');
    card.find('.reply-prenom-error').text('');
    card.find('.reply-content-error').text('');

    if (!nom || nom.length < 2) {
      card.find('.reply-nom-error').text('Veuillez entrer un nom valide (minimum 2 caractères).');
      isValid = false;
    }
    if (!prenom || prenom.length < 2) {
      card.find('.reply-prenom-error').text('Veuillez entrer un prénom valide (minimum 2 caractères).');
      isValid = false;
    }
    if (!contenu || contenu.length < 5) {
      card.find('.reply-content-error').text('Veuillez entrer une réponse d\'au moins 5 caractères.');
      isValid = false;
    }

    if (!isValid) return;

    $.post(
      '../../controller/ajouter_commentaire.php',
      { avis_id: avisId, nom: nom, prenom: prenom, contenu: contenu },
      function(resp) {
        if (resp.trim() === 'OK') {
          const newCommentHtml =
            '<div class="comment" data-comment-id="new">' +
              '<strong>' + $('<div>').text(nom + ' ' + prenom).html() + ' :</strong> ' +
              $('<div>').text(contenu).html() +
              '<div class="comment-actions">' +
                '<button class="like-btn" data-comment-id="new">👍 Like (0)</button>' +
                '<button class="dislike-btn" data-comment-id="new">👎 Dislike (0)</button>' +
                '<button class="signaler-btn" data-comment-id="new">Signaler (0)</button>' +
              '</div>' +
            '</div>';

          card.find('.existing-comments').prepend(newCommentHtml);
          card.find('.reply-nom, .reply-prenom, .reply-content').val('');
          card.find('.reply-form').slideUp();
          alert("Réponse enregistrée !");
        } else {
          console.error('Erreur lors de l\'ajout du commentaire :', resp);
          alert('Erreur : ' + resp);
        }
      }
    ).fail(function(xhr) {
      console.error('Erreur réseau (ajout commentaire) :', xhr.responseText);
      alert('Erreur réseau : ' + (xhr.responseText || 'Erreur serveur'));
    });
  });

  // Gestion des likes
  $(document).on('click', '.like-btn', function() {
    const button = $(this);
    const commentId = button.data('comment-id');

    if (commentId === 'new') {
      alert('Veuillez recharger la page pour interagir avec ce commentaire.');
      return;
    }

    $.ajax({
      url: '../../controller/crudb.php',
      method: 'POST',
      data: { action: 'like', commentaire_id: commentId },
      success: function(response) {
        if (response.trim() === 'OK') {
          const text = button.text();
          const currentCount = parseInt(text.match(/\d+/)) || 0;
          button.html('👍 Like (' + (currentCount + 1) + ')');
        } else {
          let errorMsg = response;
          try {
            const parsed = JSON.parse(response);
            errorMsg = parsed.message || response;
          } catch (e) {
            // Non-JSON response
          }
          console.error('Erreur serveur (Like) :', response);
          alert('Erreur lors de l\'ajout du Like : ' + errorMsg);
        }
      },
      error: function(xhr) {
        console.error('Erreur réseau (Like) :', xhr.responseText);
        alert('Erreur réseau lors de l\'ajout du Like : ' + (xhr.responseText || 'Erreur serveur'));
      }
    });
  });

  // Gestion des dislikes
  $(document).on('click', '.dislike-btn', function() {
    const button = $(this);
    const commentId = button.data('comment-id');

    if (commentId === 'new') {
      alert('Veuillez recharger la page pour interagir avec ce commentaire.');
      return;
    }

    $.ajax({
      url: '../../controller/crudb.php',
      method: 'POST',
      data: { action: 'dislike', commentaire_id: commentId },
      success: function(response) {
        if (response.trim() === 'OK') {
          const text = button.text();
          const currentCount = parseInt(text.match(/\d+/)) || 0;
          button.html('👎 Dislike (' + (currentCount + 1) + ')');
        } else {
          let errorMsg = response;
          try {
            const parsed = JSON.parse(response);
            errorMsg = parsed.message || response;
          } catch (e) {
            // Non-JSON response
          }
          console.error('Erreur serveur (Dislike) :', response);
          alert('Erreur lors de l\'ajout du Dislike : ' + errorMsg);
        }
      },
      error: function(xhr) {
        console.error('Erreur réseau (Dislike) :', xhr.responseText);
        alert('Erreur réseau lors de l\'ajout du Dislike : ' + (xhr.responseText || 'Erreur serveur'));
      }
    });
  });

  // Gestion du signalement
  $(document).on('click', '.signaler-btn', function() {
    const button = $(this);
    const commentId = button.data('comment-id');

    if (commentId === 'new') {
      alert('Veuillez recharger la page pour interagir avec ce commentaire.');
      return;
    }

    $.ajax({
      url: '../../controller/crudb.php',
      method: 'POST',
      data: { action: 'signaler', commentaire_id: commentId },
      success: function(response) {
        if (response.trim() === 'OK') {
          const text = button.text();
          const currentCount = parseInt(text.match(/\d+/)) || 0;
          button.html('Signaler (' + (currentCount + 1) + ')');
          alert('Commentaire signalé avec succès');
        } else {
          let errorMsg = response;
          try {
            const parsed = JSON.parse(response);
            errorMsg = parsed.message || response;
          } catch (e) {
            // Non-JSON response
          }
          console.error('Erreur serveur (Signaler) :', response);
          alert('Erreur lors du signalement du commentaire : ' + errorMsg);
        }
      },
      error: function(xhr) {
        console.error('Erreur réseau (Signaler) :', xhr.responseText);
        alert('Erreur réseau lors du signalement : ' + (xhr.responseText || 'Erreur serveur'));
      }
    });
  });
});
</script>
</body>
</html>