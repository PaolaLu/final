<template>
    <div>
      <div>
        <v-btn color="primary" @click="navigateToAbmMateria">Agregar Materia</v-btn>
      </div>
  
      <v-data-table
        :headers="headers"
        :items="listadoMaterias"
        height="400"
        item-value="name"
      >
        <template v-slot:item.actions="{ item }">
          <v-icon @click="editarMateria(item)" class="mr-2">mdi-pencil</v-icon>
          <v-icon @click="confirmarEliminarMateria(item)" class="ml-2">mdi-delete</v-icon>
        </template>
      </v-data-table>
  
      <v-dialog v-model="mostrarAbmMateria" max-width="500px" persistent>
        <AbmMateria
          :materia="materiaSeleccionada"
          :editar="editar"
          @guardar="guardarAbmMateria"
          @cancelar="cancelarAbmMateria"
        />
      </v-dialog>
  
      <v-dialog v-model="mostrarConfirmacion" max-width="500px">
        <v-card>
          <v-card-title class="headline">Confirmar Eliminación</v-card-title>
          <v-card-text>¿Está seguro de que desea eliminar esta materia?</v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="green darken-1" text @click="mostrarConfirmacion = false">Cancelar</v-btn>
            <v-btn color="red darken-1" text @click="eliminarMateriaConfirmada">Eliminar</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </div>
  </template>
  
  <script>
  import custom_axios from '../plugins/axios.js';  
  import AbmMateria from './AbmMateria.vue';
  
  export default {
    components: {
      AbmMateria,
    },
    data() {
      return {
        headers: [
          { text: 'Nombre', value: 'nombre' },
          { text: 'Cantidad de Alumnos', value: 'cant_alumnos' },
          { text: 'Carrera', value: 'carrera.nombre' },
          { text: 'Profesor', value: 'profesor.nombre' },
          { text: 'Acciones', value: 'actions', sortable: false },
        ],
        listadoMaterias: [],
        mostrarAbmMateria: false,
        mostrarConfirmacion: false,
        materiaSeleccionada: {},
        materiaAEliminar: null,
        editar: false,
      };
    },
    methods: {
      todasLasMaterias() {
        custom_axios
          .get('/apiv1/materia')
          .then(response => {
            if (response.status === 200) {
              this.listadoMaterias = response.data;
            }
          })
          .catch(error => {
            console.log(error);
          });
      },
      navigateToAbmMateria() {
        this.$router.push('/abmmateria');
      },
      editarMateria(item) {
        this.materiaSeleccionada = { ...item };
        this.editar = true;
        this.mostrarAbmMateria = true;
      },
      confirmarEliminarMateria(item) {
        this.materiaAEliminar = item;
        this.mostrarConfirmacion = true;
      },
      eliminarMateriaConfirmada() {
        custom_axios
          .delete(`/apiv1/materia/${this.materiaAEliminar.id}`)
          .then(response => {
            if (response.status === 204) {
              this.todasLasMaterias();
              this.mostrarConfirmacion = false;
            }
          })
          .catch(error => {
            console.log(error);
          });
      },
      guardarAbmMateria() {
        this.mostrarAbmMateria = false;
        this.todasLasMaterias();
      },
      cancelarAbmMateria() {
        this.mostrarAbmMateria = false;
        this.$router.push('/materia');
      },
    },
    mounted() {
      this.todasLasMaterias();
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
  