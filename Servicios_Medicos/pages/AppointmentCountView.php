import { ViewTemplate } from './ViewTemplate.js';

export class AppointmentCountView extends ViewTemplate {
  async fetchData() {
    const response = await fetch('datos_citas.php'); // este archivo PHP devuelve los datos de citas
    return await response.json();
  }

  processData(data) {
    const labels = [...new Set(data.map(item => item.fecha))]; // suponiendo fechas por día o semana
    const counts = labels.map(label => {
      const total = data
        .filter(item => item.fecha === label)
        .reduce((sum, item) => sum + item.cantidad, 0);
      return total;
    });

    return {
      labels,
      datasets: [
        {
          label: 'Citas médicas',
          data: counts,
          borderColor: this.getRandomColor(),
          backgroundColor: this.getRandomColor(0.2),
          borderWidth: 1
        }
      ]
    };
  }

  renderContent({ labels, datasets }) {
    const canvas = document.createElement('canvas');
    canvas.id = 'appointmentsChart';
    this.container.innerHTML = '';
    this.container.appendChild(canvas);

    const ctx = canvas.getContext('2d');
    new Chart(ctx, {
      type: 'line',
      data: {
        labels,
        datasets
      },
      options: {
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });
  }

  getRandomColor(alpha = 1) {
    const r = Math.floor(Math.random() * 256);
    const g = Math.floor(Math.random() * 256);
    const b = Math.floor(Math.random() * 256);
    return `rgba(${r},${g},${b},${alpha})`;
  }
}
