document.addEventListener('DOMContentLoaded', function () {
  const links = document.querySelectorAll(".nav-link");

  links.forEach(link => {
      link.addEventListener("click", () => {
          links.forEach(l => l.classList.remove("active"));
          link.classList.add("active");
      });
  });

  // Initialiser le graphique des notes si le canvas existe
  const noteChartCanvas = document.getElementById('noteChart');
  if (noteChartCanvas) {
      const statsData = noteChartCanvas.dataset.stats ? JSON.parse(noteChartCanvas.dataset.stats) : [0, 0, 0, 0, 0];

      const ctx = noteChartCanvas.getContext('2d');
      new Chart(ctx, {
          type: 'doughnut',
          data: {
              labels: ['1', '2', '3', '4', '5'],
              datasets: [{
                  label: 'Nombre d\'avis',
                  data: statsData,
                  backgroundColor: [
                      'rgba(255, 99, 132, 0.5)',
                      'rgba(255, 159, 64, 0.5)',
                      'rgba(255, 205, 86, 0.5)',
                      'rgba(75, 192, 192, 0.5)',
                      'rgba(54, 162, 235, 0.5)'
                  ],
                  borderColor: [
                      'rgba(255, 99, 132, 1)',
                      'rgba(255, 159, 64, 1)',
                      'rgba(255, 205, 86, 1)',
                      'rgba(75, 192, 192, 1)',
                      'rgba(54, 162, 235, 1)'
                  ],
                  borderWidth: 1
              }]
          },
          options: {
              responsive: true,
              plugins: {
                  legend: {
                      position: 'top'
                  },
                  title: {
                      display: true,
                      text: 'Répartition des Notes'
                  }
              }
          }
      });
  }
});

function refreshNoteStats() {
  $.ajax({
      url: '../../controller/avisController.php',
      method: 'GET',
      data: { action: 'get_note_stats' },
      success: function(response) {
          try {
              const result = JSON.parse(response);
              if (result.success) {
                  const statsData = result.data;
                  const noteChartCanvas = document.getElementById('noteChart');
                  if (noteChartCanvas) {
                      // Détruire le graphique existant pour éviter les superpositions
                      if (noteChartCanvas.chart) {
                          noteChartCanvas.chart.destroy();
                      }
                      const ctx = noteChartCanvas.getContext('2d');
                      noteChartCanvas.chart = new Chart(ctx, {
                          type: 'doughnut',
                          data: {
                              labels: ['1', '2', '3', '4', '5'],
                              datasets: [{
                                  label: 'Nombre d\'avis',
                                  data: statsData,
                                  backgroundColor: [
                                      'rgba(255, 99, 132, 0.5)',
                                      'rgba(255, 159, 64, 0.5)',
                                      'rgba(255, 205, 86, 0.5)',
                                      'rgba(75, 192, 192, 0.5)',
                                      'rgba(54, 162, 235, 0.5)'
                                  ],
                                  borderColor: [
                                      'rgba(255, 99, 132, 1)',
                                      'rgba(255, 159, 64, 1)',
                                      'rgba(255, 205, 86, 1)',
                                      'rgba(75, 192, 192, 1)',
                                      'rgba(54, 162, 235, 1)'
                                  ],
                                  borderWidth: 1
                              }]
                          },
                          options: {
                              responsive: true,
                              plugins: {
                                  legend: {
                                      position: 'top'
                                  },
                                  title: {
                                      display: true,
                                      text: 'Répartition des Notes'
                                  }
                              }
                          }
                      });
                  }
              } else {
                  console.error('Erreur dans la réponse AJAX:', result.message);
              }
          } catch (e) {
              console.error('Erreur de parsing JSON:', e);
          }
      },
      error: function(xhr, status, error) {
          console.error('Erreur lors du rafraîchissement des statistiques:', error);
      }
  });
}

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
          // Réinitialiser le graphique si la page chargée est le tableau de bord
          if (page === 'dashboard') {
              const noteChartCanvas = document.getElementById('noteChart');
              if (noteChartCanvas) {
                  const statsData = noteChartCanvas.dataset.stats ? JSON.parse(noteChartCanvas.dataset.stats) : [0, 0, 0, 0, 0];
                  const ctx = noteChartCanvas.getContext('2d');
                  new Chart(ctx, {
                      type: 'doughnut',
                      data: {
                          labels: ['1', '2', '3', '4', '5'],
                          datasets: [{
                              label: 'Nombre d\'avis',
                              data: statsData,
                              backgroundColor: [
                                  'rgba(255, 99, 132, 0.5)',
                                  'rgba(255, 159, 64, 0.5)',
                                  'rgba(255, 205, 86, 0.5)',
                                  'rgba(75, 192, 192, 0.5)',
                                  'rgba(54, 162, 235, 0.5)'
                              ],
                              borderColor: [
                                  'rgba(255, 99, 132, 1)',
                                  'rgba(255, 159, 64,  refreshments1)',
                                  'rgba(255, 205, 86, 1)',
                                  'rgba(75, 192, 192, 1)',
                                  'rgba(54, 162, 235, 1)'
                              ],
                              borderWidth: 1
                          }]
                      },
                      options: {
                          responsive: true,
                          plugins: {
                              legend: {
                                  position: 'top'
                              },
                              title: {
                                  display: true,
                                  text: 'Répartition des Notes'
                              }
                          }
                      }
                  });
              }
          }
      })
      .catch(error => {
          console.error('Erreur lors du chargement du contenu :', error);
      });
}

// Rafraîchir les statistiques toutes les 30 secondes
setInterval(refreshNoteStats, 30000);