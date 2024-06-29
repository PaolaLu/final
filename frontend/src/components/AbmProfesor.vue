<template>
    <v-form ref="form" v-model="valid" @submit.prevent="submit">
      <v-card>
        <v-card-title>{{ editar ? 'Editar Profesor' : 'Agregar Profesor' }}</v-card-title>
        <v-card-text>
          <v-text-field
            v-model="profesorData.nombre"
            label="Nombre"
            :rules="nombreRules"
            required
          ></v-text-field> 
          <v-text-field
            v-model="profesorData.apellido"
            label="Apellido"
            :rules="apellidoRules"
            required
          ></v-text-field>
          <v-text-field
            v-model="profesorData.mostrar"
            label="Mostrar"
            :rules="mostrarRules"
            required
          ></v-text-field>
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
      profesor: {
        type: Object,
        default: () => ({ id:"", nombre:"", apellido:"",mostrar:"" }),
      },
      editar: {
        type: Boolean,
        default: false,
      },
    },
    data() {
      return {
        valid: false,
        profesorData: {
          nombre: "",
          apellido: "",
          mostrar: "",
        },
        nombreRules: [(v) => !!v || 'El nombre es requerido'],
        apellidoRules: [(v) => !!v || 'El apellido es requerido'],
        mostrarRules: [(v) => !!v || 'Mostrar es requerido'],
      };
    },
    watch: {
      profesor: {
        handler(nuevoValor) {
          this.profesorData = { ...nuevoValor };
          if (!this.editar) {
            this.resetValidationForAbm();
          }
        },
        deep: true,
      },
    },
    methods: {
      submit() {
        if (this.editar) {
          this.editarProfesor();
        } else {
          this.guardarProfesor();
        }
      },
      guardarProfesor() {
        custom_axios
          .post('/apiv1/profesor', this.profesorData)
          .then((response) => {
            console.log(response);
            this.resetValidationForAbm();
            this.$emit('guardar');
            this.$router.push('/profesor'); 
          })
          .catch((error) => {
            console.log(error);
          });
      },
      editarProfesor() {
        custom_axios
          .patch(`/apiv1/profesor/${this.profesorData.id}`, this.profesorData)
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
        this.$router.push('/profesor');
      },
      resetValidationForAbm() {
        if (this.$refs.form) {
        this.$refs.form.resetValidation();
        }
      },
    },
    created() {
      if (this.profesor) {
        this.profesorData = { ...this.profesor };
      }
    },
  };
  </script>
  
  <style scoped>
  </style>
  