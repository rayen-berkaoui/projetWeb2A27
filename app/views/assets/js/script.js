// Smooth Scrolling for the Navbar Links
document.querySelectorAll('nav a').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        document.querySelector(this.getAttribute('href')).scrollIntoView({
            behavior: 'smooth'
        });
    });
});

// Simple Form Validation for Login
document.querySelector('#login form').addEventListener('submit', function (e) {
    const username = document.querySelector('[name="username"]').value;
    const password = document.querySelector('[name="password"]').value;

    if (username === "" || password === "") {
        e.preventDefault();
        alert("Both fields are required!");
    }
});
function toggleForm() {
    const loginForm = document.getElementById('login');
    const signupForm = document.getElementById('signup');
    
    if (loginForm.style.display === "none") {
        loginForm.style.display = "block";
        signupForm.style.display = "none";
    } else {
        loginForm.style.display = "none";
        signupForm.style.display = "block";
    }
}