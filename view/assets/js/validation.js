function validerFormulaire() {
  const note = parseInt(document.getElementById('note').value);
  const contenu = document.getElementById('contenu').value.trim();

  if (isNaN(note) || note < 1 || note > 5) {
    alert("La note doit être entre 1 et 5.");
    return false;
  }

  if (contenu.length < 5) {
    alert("L'avis est trop court.");
    return false;
  }

  return true;
}
function validerFormulaire() {
    const email = document.getElementById("email").value;
    const regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!regexEmail.test(email)) {
      alert("Veuillez entrer une adresse email valide.");
      return false;
    }
    return true;
  }
  
document.addEventListener('click', function (e) {
  if (e.target.classList.contains('like-button')) {
      const commentaireId = e.target.dataset.id;

      fetch('../../controller/crudb.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: new URLSearchParams({
              action: 'like',
              commentaire_id: commentaireId
          })
      })
      .then(response => response.text())
      .then(data => {
          if (data.trim() === 'OK') {
              alert('Like ajouté !');
              // Tu peux aussi recharger la page ou mettre à jour le compteur
          } else {
              alert('Erreur lors de l\'ajout du Like');
          }
      })
      .catch(error => {
          console.error('Erreur:', error);
      });
  }

  if (e.target.classList.contains('dislike-button')) {
      const commentaireId = e.target.dataset.id;

      fetch('../../controller/crudb.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: new URLSearchParams({
              action: 'dislike',
              commentaire_id: commentaireId
          })
      })
      .then(response => response.text())
      .then(data => {
          if (data.trim() === 'OK') {
              alert('Dislike ajouté !');
              // Tu peux aussi recharger la page ou mettre à jour le compteur
          } else {
              alert('Erreur lors de l\'ajout du Dislike');
          }
      })
      .catch(error => {
          console.error('Erreur:', error);
      });
  }
});
