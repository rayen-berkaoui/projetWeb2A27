<!-- avis_form.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Formulaire Avis</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="../assets/css/styleb.css" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
  <div class="container">
    <form id="avisForm" class="avis-form">
      <label for="user_id">ID Utilisateur:</label>
      <input type="text" id="user_id" name="user_id" required><br>
      
      <label for="contenu">Contenu:</label>
      <textarea id="contenu" name="contenu" required></textarea><br>

      <label for="note">Note:</label>
      <input type="number" id="note" name="note" required><br>

      <button type="submit">Envoyer</button>
    </form>
  </div>

  <script>
    $(document).ready(function() {
      $('#avisForm').on('submit', function(e) {
        e.preventDefault();  // Empêche l'envoi classique du formulaire

        // Récupérer les données du formulaire
        var formData = $(this).serialize();

        // Envoyer les données via AJAX
        $.ajax({
          url: '../controller/crudb.php',
          method: 'POST',
          data: formData + '&action=ajouter',  // Ajouter l'action 'ajouter' pour indiquer que l'on veut insérer des données
          success: function(response) {
            alert('Avis ajouté avec succès!');
            loadAvis();  // Mettre à jour l'affichage des avis
          },
          error: function() {
            alert('Erreur lors de l\'ajout de l\'avis.');
          }
        });
      });

      // Fonction pour charger les avis directement dans le dashboard
      function loadAvis() {
        $.ajax({
          url: '../controller/crudb.php', // Utiliser le même fichier pour récupérer les avis
          method: 'GET',
          data: { action: 'loadAvis' }, // Indiquer qu'on veut charger les avis
          success: function(response) {
            $('#dashboardAvis').html(response); // Insérer la liste des avis dans un div spécifique
          }
        });
      }

      // Charger les avis au chargement de la page
      loadAvis();
    });
  </script>
</body>
</html>
