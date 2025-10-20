<template>
  <div class="chart-container">
    <canvas ref="timeChart" v-if="tokens.length"></canvas>
  </div>
</template>

<script>
import { Chart, registerables } from 'chart.js';
Chart.register(...registerables);

export default {
  props: ['tokens'],
  data() {
    return {
      chart: null
    };
  },
  mounted() {
    this.tryRenderChart();
  },
  watch: {
    tokens: {
      immediate: true,
      handler() {
        this.tryRenderChart();
      }
    }
  },
  methods: {
    tryRenderChart() {
      if (!this.$refs.timeChart || !this.tokens.length) return;

      if (this.chart) this.chart.destroy();

      const grouped = {};
      this.tokens.forEach(tx => {
        const date = new Date(tx.detected_at).toISOString().split('T')[0];
        grouped[date] = (grouped[date] || 0) + parseFloat(tx.value_usd || 0);
      });

      const labels = Object.keys(grouped).sort();
      const data = labels.map(date => grouped[date]);

      this.chart = new Chart(this.$refs.timeChart.getContext('2d'), {
        type: 'line',
        data: {
          labels,
          datasets: [{
            label: 'Whale Transfer Volume (USD)',
            data,
            borderColor: '#10b981',
            backgroundColor: 'rgba(16,185,129,0.2)',
            fill: true,
            tension: 0.3
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: { display: false },
            title: {
              display: true,
              text: 'Whale Activity Over Time'
            }
          },
          scales: {
            x: { title: { display: true, text: 'Date' } },
            y: { title: { display: true, text: 'USD Value' } }
          }
        }
      });
    }
  }
};
</script>

<style scoped>
.chart-container {
  margin: 2rem auto;
  max-width: 900px;
}
</style>
