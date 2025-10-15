import { createRouter, createWebHistory } from 'vue-router';
import Dashboard from '../components/Dashboard.vue';
import Watchlist from '../components/Watchlist.vue';

const routes = [
  { path: '/', component: Dashboard },
  { path: '/watchlist', component: Watchlist },
];

export default createRouter({
  history: createWebHistory(),
  routes,
});
