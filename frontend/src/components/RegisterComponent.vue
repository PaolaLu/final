<template>
    <v-form @submit.prevent="handleRegister">
      <v-text-field v-model="username" label="Username" required></v-text-field>
      <v-text-field v-model="nombre" label="Nombre" required></v-text-field>
      <v-text-field v-model="apellido" label="Apellido" required></v-text-field>
      <v-text-field v-model="passwd" label="Password" type="password" required></v-text-field>
      <v-btn type="submit" color="primary">Registrarse</v-btn>
    </v-form>
  </template>
  
  <script>
  import AuthService from '@/services/auth.service';
  
  export default {
    data() {
      return {
        username: '',
        nombre: '',
        apellido: '',
        passwd: '',
      };
    },
    methods: {
      handleRegister() {
        AuthService.register(this.username, this.nombre, this.apellido, this.passwd)
          .then(response => {
            if (response.success) {
                  // Limpiar los campos del formulario
            this.username = '';
            this.nombre = '';
            this.apellido = '';
            this.passwd = '';
              this.$router.push('/login');
            }
          })
          .catch(error => {
            console.log(error);
          });
      }
    }
  };
  </script>
  