<template>
  <v-container>
    <v-app-bar app elevation="4">
      <v-app-bar-nav-icon @click="$emit('toogle-menu')"></v-app-bar-nav-icon>
      <v-app-bar-title>Aulas Curza</v-app-bar-title>
      <v-spacer></v-spacer>
      
      <template v-if="currentUser">
        <v-btn text small class="mr-2">
          <v-icon left>mdi-account</v-icon>
          <span v-if="currentUser">{{ currentUser.username }}</span>
        </v-btn>
        <v-btn small @click="logout">
          <v-icon left>mdi-exit-to-app</v-icon>
          Logout
        </v-btn>
      </template>
      
      <template v-else>
        <v-btn small text to="/login" class="mr-2">
          <v-icon left>mdi-login</v-icon>
          Login
        </v-btn>
        <v-btn small text to="/register">
          <v-icon left>mdi-account-plus</v-icon>
          Register
        </v-btn>
      </template>
      
      <v-btn small text fab>
        <v-icon>mdi-cog-outline</v-icon>
      </v-btn>
    </v-app-bar>
  </v-container>
</template>

<script>
import AuthService from '@/services/auth.service'; // Asegúrate de que la ruta sea correcta
import { EventBus } from '@/eventBus';

export default {
  name: "MainBar",
  data() {
    return {
      currentUser: null
    };
  },
  created() {
     // Inicializar el usuario desde localStorage
     const user = localStorage.getItem('user');
    if (user) {
      this.currentUser = JSON.parse(user);
    }

    // Escuchar el evento de inicio de sesión exitoso
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