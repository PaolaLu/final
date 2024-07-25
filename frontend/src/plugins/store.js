// store.js
import Vue from "vue";
import Vuex from "vuex";
import router from "./router";

Vue.use(Vuex);

const store = new Vuex.Store({
  state: {
    loggedIn: false,
    userInfo: {
      username: '',
      accessToken: '',
      nombre: '',
      apellido: '',
    }
  },
  mutations: {
    successfulLogin(state, userInfo) {
      state.loggedIn = true;
      state.userInfo = userInfo;
      localStorage.setItem('userInfo', JSON.stringify(userInfo));
      localStorage.setItem('loggedIn', true);
      router.push('/home');
    },
    logout(state) {
      state.loggedIn = false;
      state.userInfo = {
        username: '',
        accessToken: '',
        nombre: '',
        apellido: '',
      };
      localStorage.removeItem('userInfo');
      localStorage.removeItem('loggedIn');
      router.push('/');
    },
    initializeStore(state) {
      if (localStorage.getItem('loggedIn')) {
        state.loggedIn = true;
        state.userInfo = JSON.parse(localStorage.getItem('userInfo'));
      } else {
        state.loggedIn = false;
        state.userInfo = {
          username: '',
          accessToken: '',
          nombre: '',
          apellido: '',
        };
      }
    }
  },
  actions: {
    loginAttempt({ commit }, userInfo) {
      commit('successfulLogin', userInfo);
    },
    logoutAttempt({ commit }) {
      commit('logout');
    }
  },
  getters: {
    token: state => {
      return state.userInfo.accessToken;
    }
  }
});

store.commit('initializeStore');

export default store;
