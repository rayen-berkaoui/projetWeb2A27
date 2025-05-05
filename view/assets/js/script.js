document.getElementById('avisForm').addEventListener('submit', function(e) {
    e.preventDefault(); // Empêche l'envoi du formulaire
  
    // Récupérer les valeurs
    const nom = document.getElementById('nom').value.trim();
    const prenom = document.getElementById('prenom').value.trim();
    const commentaire = document.getElementById('commentaire').value.trim();
    const errorMsg = document.getElementById('errorMessage');
  
    // Réinitialiser le message
    errorMsg.textContent = '';
  
    // Vérifier si un champ est vide
    if (!nom || !prenom || !commentaire) {
      errorMsg.textContent = 'Veuillez remplir tous les champs du formulaire.';
      return;
    }
  
    // Si tout est bon (tu peux envoyer via fetch ici)
    alert('Formulaire envoyé avec succès !');
    // this.submit(); // si tu veux quand même l’envoyer à une page PHP
  });
  