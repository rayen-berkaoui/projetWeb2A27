function validerFormulaire() {
    let isValid = true;

    // Réinitialiser les messages d'erreur
    document.querySelectorAll('.error-message').forEach(el => el.textContent = '');

    // Validation du nom
    const nom = document.getElementById('nom').value.trim();
    if (!nom || nom.length < 2) {
        document.getElementById('nom_error').textContent = 'Veuillez entrer un nom valide (minimum 2 caractères).';
        isValid = false;
    }

    // Validation du prénom
    const prenom = document.getElementById('prenom').value.trim();
    if (!prenom || prenom.length < 2) {
        document.getElementById('prenom_error').textContent = 'Veuillez entrer un prénom valide (minimum 2 caractères).';
        isValid = false;
    }

    // Validation de l'email
    const email = document.getElementById('email').value.trim();
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!email || !emailRegex.test(email)) {
        document.getElementById('email_error').textContent = 'Veuillez entrer un email valide.';
        isValid = false;
    }

    // Validation du contenu
    const contenu = document.getElementById('contenu').value.trim();
    if (!contenu || contenu.length < 10) {
        document.getElementById('contenu_error').textContent = 'Veuillez entrer un avis d\'au moins 10 caractères.';
        isValid = false;
    }

    // Validation de la note
    const note = document.getElementById('note').value;
    if (!note || isNaN(note) || note < 1 || note > 5) {
        document.getElementById('note_error').textContent = 'Veuillez entrer une note entre 1 et 5.';
        isValid = false;
    }

    return isValid;
}