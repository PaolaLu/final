<template>
  <v-form ref="form" v-model="valid" @submit.prevent="submit">
    <v-card>
      <v-card-title>{{ editar ? 'Editar Materia' : 'Agregar Materia' }}</v-card-title>
      <v-card-text>
        <v-text-field
          v-model="materiaData.nombre"
          label="Nombre"
          :rules="nombreRules"
          required
        ></v-text-field>
        <v-text-field
          v-model="materiaData.cant_alumnos"
          label="Cantidad de Alumnos"
          :rules="cantAlumnosRules"
          required
          type="number"
        ></v-text-field>
        <v-select
          v-model="materiaData.id_carrera"
          :items="carreras"
          item-text="nombre"
          item-value="id"
          label="Carrera"
          required
        ></v-select>
        <v-select
          v-model="materiaData.id_profesor"
          :items="profesores"
          item-text="nombreCompleto"
          item-value="id"
          label="Profesor"
          required
        ></v-select>
      </v-card-text>
      <v-card-actions>
        <v-btn color="primary" @click="submit" :disabled="!valid">Guardar</v-btn>
        <v-btn color="secondary" @click="cancelar">Cancelar</v-btn>
      </v-card-actions>
    </v-card>
  </v-form>
</template>

<script>
import custom_axios from '../plugins/axios.js';

export default {
  props: {
    materia: {
      type: Object,
      default: () => ({ id: "", nombre: "", cant_alumnos: "", id_carrera: "", id_profesor: "" }),
    },
    editar: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      valid: false,
      materiaData: {
        nombre: "",
        cant_alumnos: "",
        id_carrera: null,
        id_profesor: null,
      },
      nombreRules: [(v) => !!v || 'El nombre es obligatorio'],
      cantAlumnosRules: [(v) => !!v || 'La cantidad de alumnos es obligatoria'],
      carreras: [],
      profesores: [],
    };
  },
  watch: {
    materia: {
      handler(nuevoValor) {
        this.materiaData = { ...nuevoValor };
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
        this.editarMateria();
      } else {
        this.guardarMateria();
      }
    },
    guardarMateria() {
      custom_axios
        .post('/apiv1/materia', this.materiaData)
        .then((response) => {
          console.log(response);
          this.resetValidationForAbm();
          this.$emit('guardar');
          this.$router.push('/materia');
        })
        .catch((error) => {
          console.log(error);
        });
    },
    editarMateria() {
      custom_axios
        .patch(`/apiv1/materia/${this.materiaData.id}`, this.materiaData)
        .then((response) => {
          console.log(response);
          this.resetValidationForAbm();
          this.$emit('guardar');
        })
        .catch((error) => {
          console.log(error);
        });
    },
    cancelar() {
      this.resetValidationForAbm();
      this.$emit('cancelar');
      this.$router.push('/materia');
    },
    resetValidationForAbm() {
      if (this.$refs.form) {
        this.$refs.form.resetValidation();
      }
    },
    fetchCarreras() {
      custom_axios
      .get('/apiv1/carrera')
        .then(response => {
          this.carreras = response.data;
        })
        .catch(error => {
          console.log(error);
        });
    },
    fetchProfesores() {
      custom_axios
      .get('/apiv1/profesor')
        .then(response => {
          this.profesores = response.data.map(profesor => ({
            ...profesor,
            nombreCompleto: `${profesor.nombre} ${profesor.apellido}`
          }));
        })
        .catch(error => {
          console.log(error);
        });
    },
  },
  created() {
    this.fetchCarreras();
    this.fetchProfesores();
  },
};
</script>

<style scoped>
</style>
