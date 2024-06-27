<template>
  <v-form ref="form" v-model="valid" @submit.prevent="submit">
    <v-card>
      <v-card-title>{{ editar ? 'Editar Carrera' : 'Agregar Carrera' }}</v-card-title>
      <v-card-text>
        <v-text-field
          v-model="carreraData.nombre"
          label="Nombre"
          :rules="nombreRules"
          required
        ></v-text-field>
      </v-card-text>
      <v-card-actions>
        <v-btn color="primary" @click="submit" :disabled="!valid">
          Guardar
        </v-btn>
        <v-btn color="secondary" @click="cancelar">
          Cancelar
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-form>
</template>

<script>
import custom_axios from '../plugins/axios.js';

export default {
  props: {
    carrera: {
      type: Object,
      default: () => ({
        id: "",
        nombre: "",
      }),
    },
    editar: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      valid: false,
      carreraData: {
        nombre: "",
      },
      nombreRules: [
        v => !!v || 'El nombre es obligatorio',
      ],
    };
  },
  watch: {
    carrera: {
      handler(nuevoValor) {
        this.carreraData = { ...nuevoValor };
        if (!this.editar) {
          this.resetValidationForAbm();
        }
      },
      immediate: true,
      deep: true,
    },
  },
  methods: {
    submit() {
      if (this.editar) {
        this.editarCarrera();
      } else {
        this.guardarCarrera();
      }
    },
    guardarCarrera() {
      custom_axios
        .post("/apiv1/carrera", this.carreraData)
        .then(response => {
          console.log(response);
          this.resetValidationForAbm();
          this.$emit("guardar");
          this.$router.push('/carrera');
        })
        .catch(error => {
          console.log(error);
        });
    },
    editarCarrera() {
      custom_axios
        .patch(`/apiv1/carrera/${this.carrera.id}`, this.carreraData)
        .then(response => {
          console.log(response);
          this.resetValidationForAbm();
          this.$emit("guardar");
        })
        .catch(error => {
          console.log(error);
        });
    },
    cancelar() {
      this.resetValidationForAbm();
      this.$emit("cancelar");
       this.$router.push('/carrera');
    },
    resetValidationForAbm() {
      if (this.$refs.form) {
        this.$refs.form.resetValidation();
      }
    },
  },
  created() {
    if (this.carrera) {
      this.carreraData = { ...this.carrera };
    }
  },
};
</script>

<style scoped>
</style>
