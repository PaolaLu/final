// router.js
import Vue from 'vue';
import VueRouter from "vue-router";
import LoginNuevo from '../components/LoginNuevo.vue';
import HomeAula from '../components/HomeAula.vue';
import AbmAula from '../components/AbmAula.vue';
import ListadoAula from '../components/ListadoAula.vue';
import AbmCarrera from '../components/AbmCarrera.vue';
import ListadoCarrera from '../components/ListadoCarrera.vue';
import AbmProfesor from '../components/AbmProfesor.vue';
import ListadoProfesor from '../components/ListadoProfesor.vue';
import AbmMateria from '../components/AbmMateria.vue';
import ListadoMateria from '../components/ListadoMateria.vue';
import AbmReservaAula from '../components/AbmReservaAula.vue';
import ListadoReservaAula from '../components/ListadoReservaAula.vue';
import AbmHorarioMateria from '../components/AbmHorarioMateria.vue';
import ListadoHorarioMateria from '../components/ListadoHorarioMateria.vue';
import store from "./store";

Vue.use(VueRouter);

const routes = [
    { path: '/', name: 'HomeAula', component: HomeAula },
    { path: '/abmaula', name: 'AbmAula', component: AbmAula },
    { path: '/aulas', name: 'ListadoAula', component: ListadoAula },
    { path: '/carrera', name: 'ListadoCarrera', component: ListadoCarrera },
    { path: '/abmcarrera', name: 'AbmCarrera', component: AbmCarrera },
    { path: '/abmprofesor', name: 'AbmProfesor', component: AbmProfesor },
    { path: '/profesor', name: 'ListadoProfesor', component: ListadoProfesor },
    { path: '/abmmateria', name: 'AbmMateria', component: AbmMateria },
    { path: '/materia', name: 'ListadoMateria', component: ListadoMateria },
    { path: '/abmreservaaula', name: 'AbmReservaAula', component: AbmReservaAula },
    { path: '/reservaaula', name: 'ListadoReservaAula', component: ListadoReservaAula },
    { path: '/abmhorariomateria', name: 'AbmHorarioMateria', component: AbmHorarioMateria },
    { path: '/horariomateria', name: 'ListadoHorarioMateria', component: ListadoHorarioMateria },
    { path: '/login', name: 'LoginNuevo', component: LoginNuevo },
];

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
});

router.beforeEach((to, from, next) => {
  if (to.matched.some(route => route.path) && !store.state.loggedIn && to.path !== '/login') {
    next('/login');
  } else {
    next();
  }
});

export default router;
