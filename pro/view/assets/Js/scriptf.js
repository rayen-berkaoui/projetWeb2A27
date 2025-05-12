//Animation du formulaire au chargement de la page
window.addEventListener('DOMContentLoaded', () => {
    const formContainer = document.querySelector('.form-container');
    formContainer.classList.add('fade-in');
});

// Animation de l'apparition du formulaire
const form = document.querySelector('.form-container');
form.style.opacity = 0;
form.style.transition = 'opacity 1s ease-in-out';

setTimeout(() => {
    form.style.opacity = 1;
}, 100);

// Animation du bouton de soumission
const submitButton = document.querySelector('.submit-btn');

submitButton.addEventListener('mouseover', () => {
    submitButton.style.transform = 'scale(1.05)'; // Agrandir légèrement au survol
    submitButton.style.transition = 'transform 0.3s ease';
});

submitButton.addEventListener('mouseout', () => {
    submitButton.style.transform = 'scale(1)'; // Retour à la taille normale
});

// Animation des champs de saisie au focus
const inputs = document.querySelectorAll('.form-group input, .form-group select');
inputs.forEach(input => {
    input.addEventListener('focus', () => {
        input.style.boxShadow = '0 0 10px rgba(78, 92, 122, 0.5)'; // Ajout d'un effet de focus
    });

    input.addEventListener('blur', () => {
        input.style.boxShadow = 'none'; // Retrait de l'effet de focus quand on sort du champ
    });
});   