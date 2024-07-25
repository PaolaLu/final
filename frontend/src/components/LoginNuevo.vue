<template>
    <v-container fluid fill-height>
      <v-layout align-center justify-center>
        <v-flex xs12 sm8 md4>
          <v-card class="elevation-12">
            <v-toolbar dark color="primary">
              <v-toolbar-title>Iniciar sesión</v-toolbar-title>
            </v-toolbar>
            <v-card-text>
              <v-form @submit.prevent="login">
                <v-text-field
                  v-model="username"
                  prepend-icon="mdi-account"
                  name="username"
                  label="Nombre de usuario"
                  type="text"
                  required
                ></v-text-field>
                <v-text-field
                  v-model="password"
                  prepend-icon="mdi-lock"
                  name="password"
                  label="Contraseña"
                  type="password"
                  required
                ></v-text-field>
                <v-btn color="primary" @click="login" :loading="loading">Iniciar sesión</v-btn>
              </v-form>
            </v-card-text>
          </v-card>
        </v-flex>
      </v-layout>
    </v-container>
  </template>
  
  <script>
  import { mapActions } from 'vuex';
  import custom_axios from '@/plugins/axios';
  
  export default {
    data() {
      return {
        username: '',
        password: '',
        loading: false,
      };
    },
    methods: {
      ...mapActions(['loginAttempt']), // Usa 'loginAttempt' en lugar de 'setUser' y 'setToken',
      
      async login() {
        this.loading = true;
        try {
          const response = await custom_axios.post('/auth/login', {
            username: this.username,
            passwd: this.password
          });
  
          console.log('Respuesta del servidor:', response.data);
  
          const { accessToken, nombre, username } = response.data;
          await this.loginAttempt({ nombre, username, accessToken }); // Usa 'loginAttempt' con los datos
  
          localStorage.setItem('accessToken', accessToken); // Usa localStorage
  
          this.$router.push('/'); // Redirige a la página principal
        } catch (error) {
          console.error('Error en el inicio de sesión:', error);
          if (error.response) {
            this.$vuetify.snackbar.error(error.response.data.error || 'Error en el inicio de sesión');
          } else if (error.request) {
            this.$vuetify.snackbar.error('No se pudo conectar con el servidor');
          } else {
            this.$vuetify.snackbar.error('Error al intentar iniciar sesión');
          }
        } finally {
          this.loading = false;
        }
      }
    }
  };
  </script>
  