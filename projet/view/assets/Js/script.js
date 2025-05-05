// Global Form Handling for Forgot Password, Verification Code, New Password, and Password Changed

// Function to handle form submission for Forgot Password
document.getElementById('forgot-password-form')?.addEventListener('submit', function(event) {
    event.preventDefault();
    const email = document.getElementById('email').value;
    if (email) {
        window.location.href = 'verification-code.html'; // Redirect to the Verification Code page
    } else {
        alert('Please enter a valid email address.');
    }
});

document.getElementById('verification-form')?.addEventListener('submit', function(event) {
    event.preventDefault();
    const code = document.getElementById('code').value;
    if (code) {
        window.location.href = 'new-password.html'; // Redirect to the New Password page
    } else {
        alert('Please enter the verification code.');
    }
});

document.getElementById('new-password-form')?.addEventListener('submit', function(event) {
    event.preventDefault();
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm-password').value;
    if (password === confirmPassword) {
        window.location.href = 'password-changed.html'; // Redirect to the Password Changed page
    } else {
        alert("Passwords do not match. Please try again.");
    }
});

document.getElementById('login-now-btn')?.addEventListener('click', function() {
    window.location.href = 'sign-in.html'; // Redirect to the Sign-In page
});
// Fonction pour gérer le clic sur le champ de mot de passe
document.getElementById('signupPassword').addEventListener('click', function () {
    fetchGeneratedPassword();
});

// Appel API pour obtenir le mot de passe généré
function fetchGeneratedPassword() {
    fetch('/signup.php', {  // Assurez-vous que le chemin mène à votre script PHP
        method: 'POST',
        body: JSON.stringify({ action: 'generate_password' }),
        headers: {
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        const password = data.password;
        document.getElementById('signupPassword').value = password;
        document.getElementById('passwordRecommendation').textContent = `💡 Mot de passe recommandé : ${password}`;
        document.getElementById('passwordRecommendation').classList.remove('hidden');
    })
    .catch(error => {
        console.error('Erreur lors de la génération du mot de passe:', error);
    });
// Fonction pour générer un mot de passe aléatoire
function generatePassword() {
    const length = 12; // Longueur du mot de passe
    const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()"; // Ensemble de caractères autorisés
    let password = "";
    for (let i = 0; i < length; i++) {
        const randomIndex = Math.floor(Math.random() * charset.length);
        password += charset[randomIndex];
    }
    return password;
}

// Afficher un mot de passe généré dans la boîte de recommandation
function showPasswordRecommendation() {
    const password = generatePassword(); // Générer un mot de passe
    document.getElementById('password-recommendation').innerText = "Mot de passe recommandé : " + password; // Afficher dans la boîte
    document.getElementById('signupPassword').value = password; // Mettre le mot de passe généré dans le champ
}

// Masquer la boîte de recommandation lorsque l'utilisateur clique sur le champ du mot de passe
function hidePasswordRecommendation() {
    document.getElementById('password-recommendation').style.display = 'none';
}

// Charger la recommandation de mot de passe au démarrage
window.onload = showPasswordRecommendation;


}
