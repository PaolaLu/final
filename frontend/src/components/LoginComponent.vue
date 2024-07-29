<!-- src/components/LoginComponent.vue -->
<template>
    <v-form @submit.prevent="handleLogin">
      <v-text-field v-model="username" label="Username" required></v-text-field>
      <v-text-field v-model="passwd" label="Password" type="password" required></v-text-field>
      <v-btn type="submit" color="primary">Login</v-btn>
    </v-form>
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
          .then(
            response => {
              if (response.success) {
                EventBus.$emit('loginSuccess', response.user);
                setTimeout(() => {
                this.$router.push('/');
              }, 500);
         
              }
            },
            error => {
              console.log(error);
            }
          );
      }
    }
  };
  </script>