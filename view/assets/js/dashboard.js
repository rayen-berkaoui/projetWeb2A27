document.addEventListener('DOMContentLoaded', function () {
    // Toggle submenus
    document.querySelectorAll('.nav-toggle').forEach(toggle => {
        toggle.addEventListener('click', function () {
            const parent = this.parentElement;
            const submenu = parent.querySelector('.sub-menu');

            // Toggle active class for the section
            parent.classList.toggle('active');

            // Collapse other sections
            document.querySelectorAll('.nav-section').forEach(section => {
                const otherSubmenu = section.querySelector('.sub-menu');
                if (section !== parent) {
                    section.classList.remove('active');
                    if (otherSubmenu) otherSubmenu.style.maxHeight = null;
                }
            });

            // If active, expand submenu, otherwise collapse it
            if (parent.classList.contains('active')) {
                if (submenu) {
                    submenu.style.maxHeight = submenu.scrollHeight + "px"; // Set max height to content height
                }
            } else {
                if (submenu) {
                    submenu.style.maxHeight = null; // Collapse submenu
                }
            }
        });
    });

    // Automatically expand the current active section (on page load)
    const activeSection = document.querySelector('.nav-section.active');
    if (activeSection) {
        const submenu = activeSection.querySelector('.sub-menu');
        if (submenu) submenu.style.maxHeight = submenu.scrollHeight + "px"; // Expand active submenu
    }
});
