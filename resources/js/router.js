import { createRouter, createWebHistory } from 'vue-router';
import WhaleActivityDashboard from './components/WhaleActivityDashboard.vue';

const routes = [
  { path: '/whales', component: WhaleActivityDashboard }
];

export default createRouter({
  history: createWebHistory(),
  routes
});
