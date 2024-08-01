<template>
    <v-container class="fill-height">
      <v-row align="center" justify="center">
        <v-col cols="12" sm="8" md="6" lg="4">
          <v-card class="elevation-2 pa-6">
            <v-card-title class="text-h5 justify-center mb-4">
              Registrarse
            </v-card-title>
            <v-form @submit.prevent="handleRegister">
              <v-text-field
                v-model="username"
                label="Username"
                required
                outlined
                dense
                class="mb-2"
              ></v-text-field>
              <v-text-field
                v-model="nombre"
                label="Nombre"
                required
                outlined
                dense
                class="mb-2"
              ></v-text-field>
              <v-text-field
                v-model="apellido"
                label="Apellido"
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
                <v-col cols="auto">
                  <v-btn type="submit" color="primary" elevation="2">Registrarse</v-btn>
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
  
  <style scoped>
  .v-card {
    max-width: 400px;
    width: 100%;
  }
  </style>