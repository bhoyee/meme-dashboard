import { createRouter, createWebHistory } from 'vue-router';
import Dashboard from '../components/Dashboard.vue';
import Watchlist from '../components/Watchlist.vue';
import WhaleActivityDashboard from '@/components/WhaleActivityDashboard.vue';

const routes = [
  { path: '/', component: Dashboard },
  { path: '/watchlist', component: Watchlist },
    {
    path: '/whales',
    component: WhaleActivityDashboard
  },
];

export default createRouter({
  history: createWebHistory(),
  routes,
});
