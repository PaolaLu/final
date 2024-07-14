<template>
    <div>
      <v-form ref="form" v-model="valid" @submit.prevent>
        <v-card>
          <v-card-title>Ingrese los datos</v-card-title>
          <v-card-text>
            <v-text-field
              v-model="contraseniaActual"
              label="Contraseña actual"
              required
              :rules="contraseniaRules"
              :append-icon="mostrarContrasenia ? 'mdi-eye' : 'mdi-eye-off'"
              :type="mostrarContrasenia ? 'text' : 'password'"
              @click:append="mostrarContrasenia = !mostrarContrasenia"
              @keyup.enter="submit"
            >
            </v-text-field>
            <v-text-field
              v-model="contraseniaNueva"
              label="Contraseña nueva"
              required
              :rules="contraseniaRules"
              :append-icon="mostrarContraseniaNueva ? 'mdi-eye' : 'mdi-eye-off'"
              :type="mostrarContraseniaNueva ? 'text' : 'password'"
              @click:append="mostrarContraseniaNueva = !mostrarContraseniaNueva"
              @keyup.enter="submit"
            >
            </v-text-field>
  
            <v-text-field
              v-model="contraseniaNuevaDos"
              label="Contraseña nueva"
              required
              :rules="contraseniaRules"
              :append-icon="
                mostrarContraseniaNuevaDos ? 'mdi-eye' : 'mdi-eye-off'
              "
              :type="mostrarContraseniaNuevaDos ? 'text' : 'password'"
              @click:append="
                mostrarContraseniaNuevaDos = !mostrarContraseniaNuevaDos
              "
              @keyup.enter="submit"
            >
            </v-text-field>
          </v-card-text>
          <v-card-actions>
            <v-btn
              align-self="center"
              color="primary"
              @click="submit"
              :disabled="!valid"
              >Guardar
            </v-btn>
            <v-btn color="secondary" @click="cancelar">Cancelar</v-btn>
          </v-card-actions>
        </v-card>
      </v-form>
    </div>
  </template>
  
  <script>
  
  export default {
    name: "ChangePassword",
  
    data: () => ({
      valid: false,
      contraseniaActual: "",
      contraseniaNueva: "",
      contraseniaNuevaDos: "",
      mostrarContrasenia: false,
      mostrarContraseniaNueva: false,
      mostrarContraseniaNuevaDos: false,
  
      contraseniaRules: [(v) => !!v || "La contraseña es obligatoria"],
    }),
    methods: {
      submit() {
        console.log("Contraseña actualizada!");
        this.limpiarCampos();
        this.$refs.form.resetValidation();
        this.$emit("guardar");
      },
  
      cancelar() {
        this.limpiarCampos();
        this.$refs.form.resetValidation();
        this.$emit("cancelar");
      },
      limpiarCampos() {
        this.contraseniaActual = "";
        this.contraseniaNueva = "";
        this.contraseniaNuevaDos = "";
      },
    },
  };
  </script>
  