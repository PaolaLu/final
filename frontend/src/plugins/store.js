import Vue from "vue";
import Vuex from "vuex";


Vue.use(Vuex);

const store = new Vuex.Store({
  state: {
    loggedIn: false,
    userInfo: {
      'username': '',
      'accessToken': '',
      'nombre': '',
      'apellido': '',
    }
  },
  mutations: {
    successfulLogin(state,userInfo){
      state.loggedIn = true
      state.userInfo = userInfo
      router.push('/home')
    },
    logout(state,loggedIn){
      state.loggedIn = loggedIn
      state.userInfo.username = ''
      state.userInfo.accessToken = ''
      state.userInfo.nombre = ''
      state.userInfo.apellido = ''
      console.log(state.loggedIn)
      router.push('/')
    }
  },
  actions: {
    loginAttempt({commit}, userInfo){
      console.log("login")
      commit('successfulLogin', userInfo)
    },
    logoutAttempt({commit}){
      commit('logout',false)
    }
  },
  getters: {
    token: state => {
      return state.userInfo.accessToken;
    }
  }
});

export default store;