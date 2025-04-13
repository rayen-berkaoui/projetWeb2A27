
// Animation des boutons
document.querySelectorAll('.btn-modifier, .btn-supprimer, .btn-ajouter').forEach(button => {
    button.addEventListener('mouseover', function() {
        this.style.transform = "scale(1.1)";
    });

    button.addEventListener('mouseout', function() {
        this.style.transform = "scale(1)";
    });
});

// Confirmation de suppression (facultatif)
document.querySelectorAll('.btn-supprimer').forEach(button => {
    button.addEventListener('click', function(e) {
        const confirmDelete = confirm("Êtes-vous sûr de vouloir supprimer cet utilisateur ?");
        if (!confirmDelete) {
            e.preventDefault(); // Empêche la suppression si l'utilisateur annule
        }
    });
});
// Tu peux ajouter des animations ici si tu veux, par exemple :
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