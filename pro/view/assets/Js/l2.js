let container = document.getElementById('container');

function toggle() {
  // Ajoute et enlève les classes sign-in et sign-up pour gérer la transition.
  container.classList.toggle('sign-in');
  container.classList.toggle('sign-up');
}

// Assure-toi que l'animation commence bien dès que la page est chargée.
window.addEventListener('load', () => {
  container.classList.add('sign-in');
  // Peut-être une petite pause avant de démarrer l'animation pour plus de fluidité.
  setTimeout(() => {
    container.classList.add('start-animation'); // Classe pour démarrer l'animation si nécessaire.
  }, 100); // Légère pause pour garantir la fluidité de l'animation.
});
