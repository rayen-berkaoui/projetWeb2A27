document.addEventListener("DOMContentLoaded", () => {
    const message = document.querySelector(".main-content");
    if (message) {
      message.classList.add("fade-in");
    }
  });
  document.addEventListener("DOMContentLoaded", () => {
    // Animation sur le contenu principal
    const message = document.querySelector(".main-content");
    if (message) {
        message.classList.add("fade-in");
    }

    // Validation du formulaire d'ajout
    const form = document.getElementById('formAjout');
    const errorDiv = document.getElementById('error-message');

    if (form) {
        form.addEventListener('submit', function (e) {
            const email = document.getElementById('email').value.trim();
            const numero = document.getElementById('numero').value.trim();

            // Vérification de l'email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                e.preventDefault();
                errorDiv.textContent = "L'email est invalide.";
                return;
            }

            // Vérification du numéro à 8 chiffres
            const numeroRegex = /^\d{8}$/;
            if (!numeroRegex.test(numero)) {
                e.preventDefault();
                errorDiv.textContent = "Le numéro doit contenir exactement 8 chiffres.";
                return;
            }

            // Tout est valide
            errorDiv.textContent = '';
        });
    }
});
