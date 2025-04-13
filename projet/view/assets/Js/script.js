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

// Function to handle form submission for Verification Code
document.getElementById('verification-form')?.addEventListener('submit', function(event) {
    event.preventDefault();
    const code = document.getElementById('code').value;
    if (code) {
        window.location.href = 'new-password.html'; // Redirect to the New Password page
    } else {
        alert('Please enter the verification code.');
    }
});

// Function to handle form submission for New Password
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

// Function to redirect to Login page from Password Changed page
document.getElementById('login-now-btn')?.addEventListener('click', function() {
    window.location.href = 'sign-in.html'; // Redirect to the Sign-In page
});
