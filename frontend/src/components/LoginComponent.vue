<template>
    <v-container class="fill-height">
      <v-row align="center" justify="center">
        <v-col cols="12" sm="8" md="6" lg="4">
          <v-card class="elevation-2 pa-6">
            <v-card-title class="text-h5 justify-center mb-4">
              Iniciar Sesión
            </v-card-title>
            <v-form @submit.prevent="handleLogin">
              <v-text-field
                v-model="username"
                label="Username"
                required
                outlined
                dense
                class="mb-2"
              ></v-text-field>
              <v-text-field
                v-model="passwd"
                label="Password"
                type="password"
                required
                outlined
                dense
                class="mb-4"
              ></v-text-field>
              <v-row justify="center" no-gutters>
                <v-col cols="auto" class="mr-2">
                  <v-btn type="submit" color="primary" elevation="2">Login</v-btn>
                </v-col>
                <v-col cols="auto">
                  <v-btn @click="goToRegister" color="secondary" elevation="2">Registrarse</v-btn>
                </v-col>
              </v-row>
            </v-form>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </template>
  
  <script>
  import AuthService from '@/services/auth.service';
  import { EventBus } from '@/eventBus';
  
  export default {
    data() {
      return {
        username: '',
        passwd: '',
      };
    },
    methods: {
      handleLogin() {
        AuthService.login(this.username, this.passwd)
          .then(response => {
            if (response.success) {
              EventBus.$emit('loginSuccess', response.user);
              setTimeout(() => {
                this.$router.push('/');
              }, 500);
            }
          })
          .catch(error => {
            console.log(error);
          });
      },
      goToRegister() {
        this.$router.push('/register');
      }
    }
  };
  </script>
  
  <style scoped>
  .v-card {
    max-width: 400px;
    width: 100%;
  }
  </style>