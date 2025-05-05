document.addEventListener('DOMContentLoaded', function () {
    const links = document.querySelectorAll(".nav-link");
  
    links.forEach(link => {
      link.addEventListener("click", () => {
        links.forEach(l => l.classList.remove("active"));
        link.classList.add("active");
      });
    });
  });
  

function chargerContenu(page) {
    fetch(page + '.php')
      .then(response => {
        if (!response.ok) {
          throw new Error('Erreur réseau lors du chargement de ' + page);
        }
        return response.text();
      })
      .then(html => {
        document.getElementById('main-content').innerHTML = html;
      })
      .catch(error => {
        console.error('Erreur lors du chargement du contenu :', error);
      });
  }
  
  
  
  