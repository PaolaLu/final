<template>
  <v-container class="pa-0" fluid>
    <v-app-bar app elevation="4" color="white">
      <v-app-bar-title class="text-center">
        <h2 class="text-h4 font-weight-bold">
          Sistema de Administración de Aulas Curza
        </h2>
      </v-app-bar-title>
      <v-spacer></v-spacer>
      <template v-if="currentUser">
        <v-btn text small class="mr-2">
          <v-icon left color="primary">mdi-account</v-icon>
          <span v-if="currentUser">{{ currentUser.username }}</span>
        </v-btn>
       <v-btn small @click="logout" text>
          <v-icon left color="primary">mdi-exit-to-app</v-icon>
          Salir
        </v-btn>
      </template>
      <template v-else>
       
      </template>
    </v-app-bar>
  </v-container>
</template>

<script>
import AuthService from '@/services/auth.service';
import { EventBus } from '@/eventBus';

export default {
  name: "MainBar",
  data() {
    return {
      currentUser: null
    };
  },
  created() {
    const user = localStorage.getItem('user');
    if (user) {
      this.currentUser = JSON.parse(user);
    }
    EventBus.$on('loginSuccess', (user) => {
      this.currentUser = user;
    });
  },
  methods: {
    logout() {
      AuthService.logout().then(() => {
        this.currentUser = null;
        this.$router.push('/login');
      }).catch(error => {
        console.error('Error during logout:', error);
      });
    }
  }
};
</script>

<style scoped>
.v-app-bar-title {
  display: flex;
  justify-content: center;
  width: 100%;
}
</style>