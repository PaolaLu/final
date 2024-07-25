// src/plugins/store.js
import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    user: null,
    token: null,
  },
  mutations: {
    SET_USER(state, user) {
      state.user = user;
    },
    SET_TOKEN(state, token) {
      state.token = token;
    },
    CLEAR_AUTH(state) {
      state.user = null;
      state.token = null;
    },
  },
  actions: {
    setUser({ commit }, user) {
      commit('SET_USER', user);
    },
    setToken({ commit }, token) {
      commit('SET_TOKEN', token);
      localStorage.setItem('token', token);
    },
    logout({ commit }) {
      commit('CLEAR_AUTH');
      localStorage.removeItem('token');
    },
    checkAuth({ commit }) {
      const token = localStorage.getItem('token');
      if (token) {
        commit('SET_TOKEN', token);
      }
    },
  },
  getters: {
    isAuthenticated: state => !!state.token,
    user: state => state.user,
  },
})