document.addEventListener('DOMContentLoaded', function() {
    // Toggle submenus
    document.querySelectorAll('.nav-toggle').forEach(toggle => {
        toggle.addEventListener('click', function() {
            const parent = this.parentElement;
            parent.classList.toggle('active');
            
            // Close other open sections
            if (parent.classList.contains('active')) {
                document.querySelectorAll('.nav-section').forEach(section => {
                    if (section !== parent && section.classList.contains('active')) {
                        section.classList.remove('active');
                    }
                });
            }
        });
    });
    
    // Automatically expand active section
    const activeSection = document.querySelector('.nav-section.active');
    if (activeSection) {
        activeSection.querySelector('.sub-menu').style.maxHeight = '500px';
    }
});