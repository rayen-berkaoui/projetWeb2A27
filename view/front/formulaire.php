<?php
// 1) Connexion à la BDD  
try {
    $pdo = new PDO("mysql:host=localhost;dbname=bd_avis;charset=utf8", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// 2) Si on vient de l'admin (GET ?avis_id=...), on récupère cet avis
$selectedAvis = null;
if (isset($_GET['avis_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM avis WHERE avis_id = :avis_id");
    $stmt->execute([':avis_id' => (int)$_GET['avis_id']]);
    $selectedAvis = $stmt->fetch(PDO::FETCH_ASSOC);
}

// 3) On récupère les 5 derniers avis publics
$stmt = $pdo->query("SELECT * FROM avis ORDER BY date_creation DESC LIMIT 5");
$avisPublics = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Ajouter / Afficher un Avis</title>
  <link rel="stylesheet" href="../assets/css/formulaire.css">
  <script src="../assets/js/validation.js" defer></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div style="display: flex; flex-direction: column; align-items: center;">
  <div class="form-container">
    <h2><?= $selectedAvis ? "Détails de l'avis #{$selectedAvis['avis_id']}" : "Laissez votre avis" ?></h2>
    <form id="avisForm" method="POST" onsubmit="return validerFormulaire();">
      <input type="hidden" name="action" value="ajouter">

      <label for="user_id">ID Utilisateur :</label>
      <input
        type="number" name="user_id" id="user_id"
        value="<?= $selectedAvis ? htmlspecialchars($selectedAvis['user_id']) : '' ?>"
        <?= $selectedAvis ? 'readonly' : 'required' ?> >
      <label for="email">Email :</label>
      <input 
        type="email" name="email" id="email" 
        value="<?= $selectedAvis ? htmlspecialchars($selectedAvis['email']) : '' ?>" 
        <?= $selectedAvis ? 'readonly' : 'required' ?> >


      <label for="contenu">Votre avis :</label>
      <textarea
      name="contenu" id="contenu"
      <?= $selectedAvis ? 'readonly' : 'required' ?>><?= $selectedAvis ? htmlspecialchars($selectedAvis['contenu']) : '' ?></textarea>

      <label for="note">Note (1 à 5) :</label>
      <input
        type="number" name="note" id="note" min="1" max="5"
        value="<?= $selectedAvis ? htmlspecialchars($selectedAvis['note']) : '' ?>"
        <?= $selectedAvis ? 'readonly' : 'required' ?> >

      <button type="submit">
        <?= $selectedAvis ? 'Fermer' : 'Envoyer' ?>
      </button>
    </form>
    <div id="message" style="display:none; color: green; margin-top:10px;"></div>
  </div>

  <!-- 4) Section “avis publics” -->
  <div class="avis-box-container">
    <h2>Ce que nos utilisateurs disent</h2>
    <div class="avis-grid">
    <?php foreach ($avisPublics as $a): ?>
      <div class="avis-card" data-avis-id="<?= $a['avis_id'] ?>">

        <!-- ① Affichage des commentaires EXISTANTS au-dessus du contenu -->
<div class="existing-comments">
  <?php
  // Récupération des commentaires pour cet avis
  $fetchCom = $pdo->prepare("SELECT * FROM commentaires WHERE avis_id = :avis_id ORDER BY date_creation DESC");
  $fetchCom->execute([':avis_id' => $a['avis_id']]);
  foreach ($fetchCom->fetchAll(PDO::FETCH_ASSOC) as $c):
  ?>
    <div class="comment">
      <strong>#<?= $c['user_id'] ?> :</strong> <?= htmlspecialchars($c['contenu']) ?>

      <!-- Boutons Like et Dislike -->
      <div class="like-dislike-container">
        <button class="like-btn" data-comment-id="<?= $c['commentaire_id'] ?>">
          👍 Like (<?= $c['likes'] ?>)
        </button>
        <button class="dislike-btn" data-comment-id="<?= $c['commentaire_id'] ?>">
          👎 Dislike (<?= $c['dislikes'] ?>)
        </button>
      </div>

      <!-- Bouton "Signaler" -->
      <button class="signaler-btn" data-comment-id="<?= $c['commentaire_id'] ?>">Signaler</button>
    </div>
  <?php endforeach; ?>
</div>



        <!-- ② Contenu de l’avis -->
        <div class="quote">“</div>
        <p class="avis-text"><?= htmlspecialchars($a['contenu']) ?></p>
        <div class="avis-author">…</div>

        <!-- ③ Formulaire de réponse -->
        <button class="reply-btn">Répondre</button>
        <div class="reply-form" style="display:none;">
          <input type="number" class="reply-user" placeholder="Votre ID" required>
          <textarea class="reply-content" placeholder="Votre réponse…"></textarea>
          <button class="submit-reply">Envoyer</button>
        </div>

      </div>
    <?php endforeach; ?>
  </div>

  <!-- 5) Script AJAX pour ajouter un nouvel avis si on n'est pas en mode “lecture seule” -->
  <?php if (!$selectedAvis): ?>
  <script>
    $(function() {
      $('#avisForm').on('submit', function(e) {
        e.preventDefault();
        if (!validerFormulaire()) return;

        $.post('../../controller/crudb.php',
          $(this).serialize(),
          function(resp) {
            $('#message').text("Avis ajouté avec succès !").show();
            $('#avisForm')[0].reset();
          }
        ).fail(function(){
          $('#message').text("Erreur, réessayez.").css('color','red').show();
        });
      });
    });
  </script>
  <?php endif; ?>
  </div>

  <?php if ($selectedAvis): ?>
  <script>
    document.querySelector('form button').addEventListener('click', function(e) {
      e.preventDefault();
      window.location.href = 'formulaire.php';
    });
  </script>
  <?php endif; ?>

  <script>
    $(function(){
      // ➊ Ouvre/ferme le formulaire de réponse
      $('.reply-btn').on('click', function(){
        $(this).siblings('.reply-form').slideToggle(200);
      });

      // ➋ Soumet la réponse
      $('.submit-reply').on('click', function(){
        const card = $(this).closest('.avis-card');
        const avisId = card.data('avis-id');
        const userId = card.find('.reply-user').val().trim();
        const contenu = card.find('.reply-content').val().trim();
        if (!userId || !contenu) {
          alert("Veuillez entrer votre ID et votre réponse.");
          return;
        }

        $.post(
          '../../controller/ajouter_commentaire.php',
          { avis_id: avisId, user_id: userId, contenu: contenu },
          function(resp){
            if (resp.trim() === 'OK') {
              const newCommentHtml = 
                '<div class="comment">' +
                  '<strong>#' + userId + ' :</strong> ' +
                  $('<div>').text(contenu).html() +
                '</div>';
              
              card.find('.existing-comments').prepend(newCommentHtml);
              card.find('.reply-user, .reply-content').val('');
              card.find('.reply-form').slideUp();
              alert("Réponse enregistrée !");
            } else {
              alert("Erreur : " + resp);
            }
          }
        ).fail(() => alert("Erreur réseau"));
      });
    });

    $(function() {
      $('.like-btn').on('click', function() {
  var button = $(this); 
  var commentId = button.data('comment-id');
  
  $.ajax({
    url: '../../controller/crudb.php',
    method: 'POST',
    data: { action: 'like', commentaire_id: commentId },
    success: function(response) {
      if (response.trim() === 'OK') {
        var text = button.text();
        var currentCount = parseInt(text.match(/\d+/)) || 0;
        button.html('👍 Like (' + (currentCount + 1) + ')');
      } else {
        alert('Erreur lors de l\'ajout du Like');
      }
    }
  });
});

$('.dislike-btn').on('click', function() {
  var button = $(this);
  var commentId = button.data('comment-id');
  
  $.ajax({
    url: '../../controller/crudb.php',
    method: 'POST',
    data: { action: 'dislike', commentaire_id: commentId },
    success: function(response) {
      if (response.trim() === 'OK') {
        var text = button.text();
        var currentCount = parseInt(text.match(/\d+/)) || 0;
        button.html('👎 Dislike (' + (currentCount + 1) + ')');
      } else {
        alert('Erreur lors de l\'ajout du Dislike');
      }
    }
  });
});


    });
  </script>
<script>
$(function() {
  // Lorsque l'utilisateur clique sur "Signaler"
  $('.signaler-btn').on('click', function() {
    var button = $(this);
    var commentId = button.data('comment-id');
    
    // Envoi de la requête AJAX pour signaler le commentaire
    $.ajax({
      url: '../../controller/crudb.php',
      method: 'POST',
      data: { action: 'signaler', commentaire_id: commentId },
      success: function(response) {
        if (response.trim() === 'OK') {
          alert('Commentaire signalé avec succès');
        } else {
          alert('Erreur lors du signalement du commentaire');
        }
      }
    });
  });
});
</script>


</body>
</html>
