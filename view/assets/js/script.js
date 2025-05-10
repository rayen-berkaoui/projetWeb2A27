// Smooth Scrolling for the Navbar Links
document.querySelectorAll('nav a').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        document.querySelector(this.getAttribute('href')).scrollIntoView({
            behavior: 'smooth'
        });
    });
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
// Form Validation for Create Article Page
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    if (!form) return; // Skip if there's no form on this page

    const typeInput = document.getElementById('type');
    const authorInput = document.getElementById('author');
    const contentInput = document.getElementById('content');

    form.addEventListener('submit', function (e) {
        let valid = true;

        // Reset borders
        [typeInput, authorInput, contentInput].forEach(input => {
            input.style.borderColor = '#ccc';
        });

        // Type validation
        if (typeInput.value.trim().length < 3) {
            typeInput.style.borderColor = 'red';
            alert("Type must be at least 3 characters.");
            valid = false;
        }

        // Author validation
        else if (!/^[a-zA-Z\s]+$/.test(authorInput.value.trim())) {
            authorInput.style.borderColor = 'red';
            alert("Author name must contain only letters and spaces.");
            valid = false;
        }

        // Content validation
        else if (contentInput.value.trim().length < 10) {
            contentInput.style.borderColor = 'red';
            alert("Content must be at least 10 characters long.");
            valid = false;
        }

        if (!valid) {
            e.preventDefault();
        }
    });
});
