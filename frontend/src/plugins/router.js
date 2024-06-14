import Vue from 'vue';
import Router from 'vue-router';

import HomeAula from '../components/HomeAula.vue';
import AboutAula from '../components/AboutAula.vue';


Vue.use(Router);

const routes = [
  { path: '/', name: 'HomeAula', component: HomeAula },
  { path: '/about', name: 'AboutAula', component: AboutAula },

];

const router = new Router({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
});

export default router;