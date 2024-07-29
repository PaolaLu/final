<!-- src/components/RegisterComponent.vue -->
<template>
    <v-form @submit.prevent="handleRegister">
      <v-text-field v-model="username" label="Username" required></v-text-field>
      <v-text-field v-model="password" label="Password" type="password" required></v-text-field>
      <v-btn type="submit" color="primary">Register</v-btn>
    </v-form>
  </template>
  
  <script>
  import AuthService from '@/services/auth.service';
  
  export default {
    data() {
      return {
        username: '',
        password: '',
      };
    },
    methods: {
      handleRegister() {
        AuthService.register(this.username, this.password)
          .then(
            response => {
              if (response.data.success) {
                this.$router.push('/login');
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