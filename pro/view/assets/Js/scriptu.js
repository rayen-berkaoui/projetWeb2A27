
// Animation des boutons
document.querySelectorAll('.btn-modifier, .btn-supprimer, .btn-ajouter').forEach(button => {
    button.addEventListener('mouseover', function() {
        this.style.transform = "scale(1.1)";
    });

    button.addEventListener('mouseout', function() {
        this.style.transform = "scale(1)";
    });
});

document.querySelectorAll('.btn-supprimer').forEach(button => {
    button.addEventListener('click', function(e) {
        const confirmDelete = confirm("Êtes-vous sûr de vouloir supprimer cet utilisateur ?");
        if (!confirmDelete) {
            e.preventDefault(); 
        }
    });
});

document.querySelectorAll(".btn-action").forEach(btn => {
    btn.addEventListener("mouseover", () => {
      btn.style.boxShadow = "0 4px 10px rgba(0,0,0,0.2)";
    });
    btn.addEventListener("mouseout", () => {
      btn.style.boxShadow = "none";
    });
  });
  document.getElementById('checkAll').addEventListener('click', function() {
    let checkboxes = document.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(function(checkbox) {
      checkbox.checked = document.getElementById('checkAll').checked;
    });
  });
  function searchUsers() {
    // Récupère la valeur de la recherche
    let searchQuery = document.getElementById('search').value.toLowerCase();
    
    // Récupère toutes les lignes de la table
    let rows = document.querySelectorAll('#userTableBody tr');
    
    // Parcourt toutes les lignes
    rows.forEach(row => {
        // Récupère le texte du nom d'utilisateur
        let username = row.querySelector('.username').textContent.toLowerCase();
        
        // Si le nom d'utilisateur contient la recherche, met en surbrillance la ligne
        if (username.includes(searchQuery)) {
            row.classList.add('highlight');
        } else {
            row.classList.remove('highlight');
        }
    });
}
