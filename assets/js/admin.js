// admin.js
document.addEventListener('DOMContentLoaded', function() {

  // Chart: distribuição de status
  try {
    const ctx = document.getElementById('statusChart').getContext('2d');
    const chart = new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: statusLabels,
        datasets: [{
          label: 'Livros',
          data: statusCounts,
          backgroundColor: [
            '#4e73df','#063336ff','#36b9cc','#f6c23e','#e74a3b','#858796'
          ],
          borderWidth: 1
        }]
      },
      options: {
        plugins: {
          legend: { position: 'bottom' }
        },
        maintainAspectRatio: false
      }
    });
  } catch(e) { console.warn(e); }

  // Pequena animação de cards (opcional)
  const cards = document.querySelectorAll('.card-counter');
  cards.forEach((c,i) => {
    c.style.opacity = 0;
    setTimeout(()=>{ c.style.transition = 'opacity .5s ease'; c.style.opacity = 1; }, 80*i);
  });
});
