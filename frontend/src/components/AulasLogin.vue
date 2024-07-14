<template>
  <div class="login-container">
    <v-form ref="form" v-model="valid" @submit.prevent class="login-form">
      <v-card>
        <v-card-title>Ingreso</v-card-title>
        <v-card-text>
          <v-text-field
            v-model="usuario"
            label="Usuario"
            required
            :rules="usuarioRules"
          >
          </v-text-field>
          <v-text-field
            v-model="contrasenia"
            label="Contraseña"
            required
            :rules="contraseniaRules"
            :append-icon="mostrarContrasenia ? 'mdi-eye' : 'mdi-eye-off'"
            :type="mostrarContrasenia ? 'text' : 'password'"
            @click:append="mostrarContrasenia = !mostrarContrasenia"
            @keyup.enter="ingresar"
          >
          </v-text-field>
        </v-card-text>
        <v-card-actions>
          <v-btn
            align-self="center"
            color="primary"
            @click="ingresar"
            :disabled="!valid"
            >Ingresar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-form>
    <v-snackbar
      v-model="snackbar"
      :timeout="timeout"
      color="red darken-2"
    >
      {{ snackbarText }}
    </v-snackbar>
  </div>
</template>

<script>
export default {
  data: () => ({
    valid: false,
    usuario: "",
    contrasenia: "",
    mostrarContrasenia: false,
    usuarioRules: [(v) => !!v || "El usuario es obligatorio"],
    contraseniaRules: [(v) => !!v || "La contraseña es obligatoria"],
    snackbar: false,
    snackbarText: '',
    timeout: 4000,
  }),
  methods: {
    ingresar() {
      const data = {
        username: this.usuario,
        passwd: this.contrasenia
      };
      var that = this;
      this.axios
        .post("/auth/login", data)
        .then(function (response) {
          if (response.status === 200){
            that.$store.dispatch('loginAttempt', response.data)
          }
        })
        .catch(function (error) {
          if (error.response && error.response.status === 401){
            that.snackbarText = 'Credenciales ingresadas incorrectas';
          } else {
            that.snackbarText = 'Ocurrió un Error'
          }
          that.snackbar = true;
        })
        .then(function () {
          // Puedes agregar código aquí si necesitas hacer algo después de la petición,
          // independientemente de si fue exitosa o falló
        });
    },
  },
};
</script>

<style lang="scss">
.login-container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
}
.login-form {
  min-width: 400px;
}
</style>