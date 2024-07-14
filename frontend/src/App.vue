<template>
  <v-app>
    <AulasLogin v-if="!$store.state.loggedIn"> </AulasLogin>
    <MainMenu v-if="$store.state.loggedIn"></MainMenu>
    <MainBar v-if="$store.state.loggedIn"></MainBar>
    <v-main v-if="$store.state.loggedIn">
      <v-container fluid> <router-view /> </v-container>
    </v-main>
  </v-app>
</template>

<script>
import MainMenu from "./components/MainMenu.vue";
import MainBar from "./components/MainBar.vue";
import AulasLogin from "./components/AulasLogin.vue";
export default {
  name: "App",

  components: {
    MainMenu,
    MainBar,
    AulasLogin,
  },

  data: () => ({
    //
  }),
};
</script>

<style lang="scss">
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
}

nav {
  padding: 30px;

  a {
    font-weight: bold;
    color: #2c3e50;

    &.router-link-exact-active {
      color: #42b983;
    }
  }
}
</style>
