<template>
    <div class="aula-container">
      <div class="button-container">
       <v-btn 
        color="primary"
         @click="navigateToAbmProfesor"
         class="custom-button mr-4"
      >
        <v-icon left>mdi-school</v-icon>
         Agregar Profesor</v-btn>
         <v-btn
         color="primary" 
         @click="navigateToHome"
         class="custom-button"
      >
        <v-icon left>mdi-home</v-icon>
        Volver a Home
      </v-btn>
        </div>

      <v-data-table
        :headers="headers"
        :items="listadoProfesor"
        height="400"
        item-value="name"
      >
        <template v-slot:item.actions="{ item }">
          <v-icon color="green" @click="editarProfesor(item)" class="mr-2">mdi-pencil</v-icon>
          <v-icon color="red" @click="confirmarEliminarProfesor(item)" class="ml-2">mdi-delete</v-icon>
        </template>
      </v-data-table>
  
      <v-dialog v-model="mostrarAbmProfesor" max-width="500px" persistent>
        <AbmProfesor
          :profesor="profesorSeleccionado"
          :editar="editar"
          @guardar="guardarAbmProfesor"
          @cancelar="cancelarAbmProfesor"
        />
      </v-dialog>
  
      <v-dialog v-model="mostrarConfirmacion" max-width="500px">
        <v-card>
          <v-card-title class="headline">Confirmar Eliminación</v-card-title>
          <v-card-text>¿Está seguro de que desea eliminar el profesor?</v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="green darken-1" text @click="mostrarConfirmacion = false">Cancelar</v-btn>
            <v-btn color="red darken-1" text @click="eliminarProfesorConfirmado">Eliminar</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </div>
  </template>
  
  <script>
  import custom_axios from '../plugins/axios.js';  
  import AbmProfesor from './AbmProfesor.vue';
  
  export default {
    components: {
        AbmProfesor,
    },
    data() {
      return {
        headers: [
          { text: 'Nombre', value: 'nombre' },
          { text: 'Apellido', value: 'apellido' },
          { text: 'Mostrar', value: 'mostrar' },
          { text: 'Acciones', value: 'actions', sortable: false },
        ],
        listadoProfesor: [],
        mostrarAbmProfesor: false,
        mostrarConfirmacion: false,
        profesorSeleccionado: {},
        profesorAEliminar: null,
        editar: false,
      };
    },
    methods: {
      todosLosProfesores() {
        custom_axios
          .get('/apiv1/profesor')
          .then(response => {
            if (response.status === 200) {
              this.listadoProfesor = response.data;
            }
          })
          .catch(error => {
            console.log(error);
          });
      },
      navigateToAbmProfesor() {
        this.$router.push('/abmprofesor');
      },

      navigateToHome() {
      this.$router.push('/');
      },
      
      editarProfesor(item) {
        this.profesorSeleccionado = { ...item };
        this.editar = true;
        this.mostrarAbmProfesor = true;
      },
      confirmarEliminarProfesor(item) {
        this.profesorAEliminar = item;
        this.mostrarConfirmacion = true;
      },
      eliminarProfesorConfirmado() {
        custom_axios
          .delete(`/apiv1/profesor/${this.profesorAEliminar.id}`)
          .then(response => {
            if (response.status === 204) {
              this.todosLosProfesores();
              this.mostrarConfirmacion = false;
             
            }
          })
          .catch(error => {
            console.log(error);
          });
      },
      guardarAbmProfesor() {
        this.mostrarAbmProfesor = false;
        this.todosLosProfesores();
      },
      cancelarAbmProfesor() {
        this.mostrarAbmProfesor = false;
        this.$router.push('/abmprofesor');
      },
    },
    mounted() {
      this.todosLosProfesores();
    },
  };
  </script>
  
  <style scoped>
  .mr-2 {
    margin-right: 8px;
  }
  .ml-2 {
    margin-left: 8px;
  }
  </style>