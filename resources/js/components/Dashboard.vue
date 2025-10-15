<template>
  <div>
    <h1>Meme Coin Dashboard</h1>
    <ul>
      <li v-for="token in tokens" :key="token.id">
        {{ token.name }} - ${{ token.volume }}
      </li>
    </ul>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return { tokens: [] };
  },
  mounted() {
    axios.get('/api/tokens').then(res => {
      this.tokens = res.data;
    });
  },
};
</script>
