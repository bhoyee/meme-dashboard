<template>
  <div class="chart-container" v-if="tokens.length">
    <canvas ref="chartCanvas"></canvas>
  </div>
</template>

<script>
import { Chart, registerables } from 'chart.js';
import { nextTick } from 'vue';
Chart.register(...registerables);

export default {
  props: ['tokens'],
  data() {
    return {
      chart: null
    };
  },
  watch: {
    tokens: {
      immediate: true,
      handler(newTokens) {
        if (Array.isArray(newTokens) && newTokens.length > 0) {
          nextTick(() => this.renderChart(newTokens));
        }
      }
    }
  },
  methods: {
    renderChart(tokens) {
      if (!this.$refs.chartCanvas) {
        console.error('Canvas not found');
        return;
      }

      if (this.chart) this.chart.destroy();

      const tokenGroups = tokens.reduce((acc, tx) => {
        const name = tx.token_name || 'Unknown';
        acc[name] = (acc[name] || 0) + parseFloat(tx.value_usd || 0);
        return acc;
      }, {});

      const labels = Object.keys(tokenGroups);
      const data = Object.values(tokenGroups);

      try {
        this.chart = new Chart(this.$refs.chartCanvas.getContext('2d'), {
          type: 'bar',
          data: {
            labels,
            datasets: [{
              label: 'Whale Transfer Value (USD)',
              data,
              backgroundColor: '#38bdf8'
            }]
          },
          options: {
            responsive: true,
            plugins: {
              legend: { display: false },
              title: {
                display: true,
                text: 'Token Value Distribution'
              }
            }
          }
        });
      } catch (err) {
        console.error('Failed to create chart:', err);
      }
    }
  }
};
</script>

<style scoped>
.chart-container {
  margin: 2rem auto;
  max-width: 800px;
}
</style>
