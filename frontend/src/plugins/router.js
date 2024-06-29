import Vue from 'vue';
import Router from 'vue-router';

import HomeAula from '../components/HomeAula.vue';
import AboutAula from '../components/AboutAula.vue';
import AbmAula from '../components/AbmAula.vue';
import ListadoAula from '../components/ListadoAula.vue';
import AbmCarrera from '../components/AbmCarrera.vue';
import ListadoCarrera from '../components/ListadoCarrera.vue';
import AbmProfesor from '../components/AbmProfesor.vue';
import ListadoProfesor from '../components/ListadoProfesor.vue';
import AbmMateria from '../components/AbmMateria.vue';
import AbmReservaAula from '../components/AbmReservaAula.vue';
import AbmHorarioMateria from '../components/AbmHorarioMateria.vue';


Vue.use(Router);

const routes = [
  { path: '/', name: 'HomeAula', component: HomeAula },
  { path: '/about', name: 'AboutAula', component: AboutAula },
  { path: '/abmaula', name: 'AbmAula', component: AbmAula },
  { path: '/aulas', name: 'ListadoAula', component: ListadoAula },
  { path: '/carrera', name: 'ListadoCarrera', component: ListadoCarrera },
  { path: '/abmcarrera', name: 'AbmCarrera', component: AbmCarrera },
  { path: '/abmprofesor', name: 'AbmProfesor', component: AbmProfesor },
  { path: '/profesor', name: 'ListadoProfesor', component: ListadoProfesor },
  { path: '/materia', name: 'AbmMateria', component: AbmMateria },
  { path: '/reservaaula', name: 'AbmReservaAula', component: AbmReservaAula },
  { path: '/horariomateria', name: 'AbmHorarioMateria', component: AbmHorarioMateria },
];

const router = new Router({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
});

export default router;